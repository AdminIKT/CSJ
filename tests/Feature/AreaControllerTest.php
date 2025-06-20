<?php

use App\Entities\Area;
use App\Entities\Account;
use App\Entities\User;
use App\Entities\Role;
use App\Entities\Settings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(function () {
    // Limpiar la base de datos al inicio
    // $this->artisan('doctrine:schema:drop --force --env=testing');
    // $this->artisan('doctrine:schema:create --env=testing');
    
    // Obtener el EntityManager
    $this->em = app(EntityManagerInterface::class);

    // Iniciar transacción que se revertirá automáticamente
    $this->em->beginTransaction();
    
    // CREAR ROLES DIRECTAMENTE EN LA BASE DE DATOS
    createAllRoles();
    
    // Obtener el rol admin desde la BD
    $adminRole = getRoleById(Role::ROLE_ADMIN);

    $this->user = createAdminUser('admin@example.com','Admin User');
    
    // Crear configuración de año actual
    createCurrentYearSetting();
    
    // Autenticar al usuario ADMIN
    Auth::login($this->user);
});

afterEach(function () {
    Auth::logout();

    // Revertir la transacción - esto limpia todo automáticamente
    if ($this->em->getConnection()->isTransactionActive()) {
        $this->em->rollback();
    }
});

// TESTS ESPECÍFICOS PARA DIFERENTES ROLES
describe('Role-based Access Tests', function () {
    test('admin user can access areas', function () {
        // El usuario ya es admin por el beforeEach
        $response = $this->get(route('areas.index'));
        $response->assertStatus(200);
    });
    
    test('non-admin user cannot access areas', function () {
        Auth::logout();
        
        $regularUser = createUserWithRole(Role::ROLE_SALES);
        Auth::login($regularUser);
        
        $response = $this->get(route('areas.index'));
        $response->assertStatus(403);
    });
    
    test('unauthenticated user is redirected to login', function () {
        Auth::logout();
        
        $response = $this->get(route('areas.index'));
        $response->assertRedirect('/redirect');
    });
});

describe('Areas Index', function () {
    test('displays areas index page', function () {
        $response = $this->get(route('areas.index'));
        
        $response->assertStatus(200)
                ->assertViewIs('areas.index')
                ->assertViewHas('collection');
    });
    
    test('displays areas ordered by acronym desc', function () {
        // Crear múltiples áreas
        $area1 = new Area();
        $area1->setName('Area Alpha')->setAcronym('AAA');
        
        $area2 = new Area();
        $area2->setName('Area Beta')->setAcronym('BBB');
        
        $area3 = new Area();
        $area3->setName('Area Gamma')->setAcronym('CCC');
        
        $this->em->persist($area1);
        $this->em->persist($area2);
        $this->em->persist($area3);
        $this->em->flush();
        
        // GET request.
        $response = $this->get(route('areas.index'));
        
        // Assert HTTP code.
        $response->assertStatus(200);
        
        // Verificar que están ordenadas correctamente en la respuesta del Controller.
        $areas = $response->viewData('collection');
        expect($areas[0]->getAcronym())->toBe('CCC');
        expect($areas[1]->getAcronym())->toBe('BBB');
        expect($areas[2]->getAcronym())->toBe('AAA');

        // Verificar respuesta HTML.
        $response->assertSeeInOrder(['CCC', 'BBB', 'AAA']);
    });
});

describe('Areas Create', function () {
    test('displays create form', function () {
        $response = $this->get(route('areas.create'));
        
        $response->assertStatus(200)
                ->assertViewIs('areas.form')
                ->assertViewHas(['route', 'method', 'entity']);
    });
    
    test('create form has correct data', function () {
        $response = $this->get(route('areas.create'));
        
        expect($response->viewData('route'))->toBe(route('areas.store'));
        expect($response->viewData('method'))->toBe('POST');
        expect($response->viewData('entity'))->toBeInstanceOf(Area::class);
    });
});

describe('Areas Store', function () {
    test('creates new area with valid data', function () {
        $data = [
            'name' => 'Test Area',
            'acronym' => 'TST'
        ];
        
        $response = $this->post(route('areas.store'), $data);
        
        $response->assertRedirect(route('areas.index'))
                ->assertSessionHas('success', __('Successfully created'));
        
        // Verificar que se creó en la base de datos
        $area = $this->em->getRepository(Area::class)
                        ->findOneBy(['acronym' => 'TST']);
        
        expect($area)->not->toBeNull();
        expect($area->getName())->toBe('Test Area');
        expect($area->getAcronym())->toBe('TST');
    });
    
    test('validation fails with empty name', function () {
        $data = [
            'name' => '',
            'acronym' => 'TST'
        ];
        
        $response = $this->post(route('areas.store'), $data);
        
        $response->assertSessionHasErrors(['name']);
    });
    
    test('validation fails with empty acronym', function () {
        $data = [
            'name' => 'Test Area',
            'acronym' => ''
        ];
        
        $response = $this->post(route('areas.store'), $data);
        
        $response->assertSessionHasErrors(['acronym']);
    });
});

// TODO: Fix show BUG. $perPage variable doesn't exis in vies/accounts/shared/search.blade.php
/*describe('Areas Show', function () {
    test('displays area with accounts', function () {
        $area = new Area();
        $area->setName('Test Area')->setAcronym('TST');
        $this->em->persist($area);
        $this->em->flush();
        
        $response = $this->get(route('areas.show', ['area' => $area->getId()]));
        
        $response->assertStatus(200)
                ->assertViewIs('areas.show')
                ->assertViewHas(['entity', 'collection']);
    });
    
    test('show page filters accounts by area', function () {
        $area = new Area();
        $area->setName('Test Area')->setAcronym('TST');
        $this->em->persist($area);
        $this->em->flush();
        
        $response = $this->get(route('areas.show', ['area' => $area->getId()]));
        
        expect($response->viewData('entity'))->toBeInstanceOf(Area::class);
        expect($response->viewData('entity')->getId())->toBe($area->getId());
    });
    
    test('respects perPage parameter', function () {
        $area = new Area();
        $area->setName('Test Area')->setAcronym('TST');
        $this->em->persist($area);
        $this->em->flush();
        
        $response = $this->get(route('areas.show', [
            'area' => $area->getId(),
            'perPage' => 15
        ]));
        
        $response->assertStatus(200);
    });
});*/
