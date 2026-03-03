<?php

use App\Entities\User;
use App\Entities\Role;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    $this->em = app(EntityManagerInterface::class);
    $this->em->beginTransaction();

    createAllRoles();
    $this->user = createAdminUser('admin@example.com','Admin User');
    createCurrentYearSetting();
    Auth::login($this->user);
});

afterEach(function () {
    Auth::logout();
    if ($this->em->getConnection()->isTransactionActive()) {
        $this->em->rollback();
    }
});

describe('User Controller - Access', function () {
    test('admin user can access users index', function () {
        $response = $this->get(route('users.index'));
        $response->assertStatus(200);
    });

    test('non-admin user cannot access users index', function () {
        Auth::logout();
        $regularUser = createUserWithRole(Role::ROLE_SALES);
        Auth::login($regularUser);
        $response = $this->get(route('users.index'));
        $response->assertStatus(403);
    });

    test('unauthenticated user is redirected to login', function () {
        Auth::logout();
        $response = $this->get(route('users.index'));
        $response->assertRedirect('/redirect');
    });
});

describe('Users Index and Filters', function () {
    test('displays users index view with collection', function () {
        $response = $this->get(route('users.index'));
        $response->assertStatus(200)
                 ->assertViewIs('users.index')
                 ->assertViewHas('collection');
    });

    test('default shows only active users', function () {
        $active = new User();
        $active->setEmail('active@example.com')->setName('Active')->setStatus(User::STATUS_ACTIVE);

        $inactive = new User();
        $inactive->setEmail('inactive@example.com')->setName('Inactive')->setStatus(User::STATUS_INACTIVE);

        $this->em->persist($active);
        $this->em->persist($inactive);
        $this->em->flush();

        $response = $this->get(route('users.index', ['perPage' => ''])); // perPage empty => no pagination
        $response->assertStatus(200);

        $collection = $response->viewData('collection');
        $emails = array_map(function($u) { return $u->getEmail(); }, $collection);
        expect(in_array('active@example.com', $emails))->toBeTrue();
        expect(in_array('inactive@example.com', $emails))->toBeFalse();
    });

    test('empty status selection shows both active and inactive', function () {
        $a = new User();
        $a->setEmail('a1@example.com')->setName('A1')->setStatus(User::STATUS_ACTIVE);
        $b = new User();
        $b->setEmail('b1@example.com')->setName('B1')->setStatus(User::STATUS_INACTIVE);
        $this->em->persist($a);
        $this->em->persist($b);
        $this->em->flush();

        $response = $this->get(route('users.index', ['status' => '', 'perPage' => '']));
        $response->assertStatus(200);
        $collection = $response->viewData('collection');
        $emails = array_map(function($u) { return $u->getEmail(); }, $collection);
        expect(in_array('a1@example.com', $emails))->toBeTrue();
        expect(in_array('b1@example.com', $emails))->toBeTrue();
    });

    test('filter by explicit status inactive', function () {
        $ia = new User();
        $ia->setEmail('ia@example.com')->setName('IA')->setStatus(User::STATUS_INACTIVE);
        $this->em->persist($ia);
        $this->em->flush();

        $response = $this->get(route('users.index', ['status' => User::STATUS_INACTIVE, 'perPage' => '']));
        $response->assertStatus(200);
        $collection = $response->viewData('collection');
        $emails = array_map(function($u) { return $u->getEmail(); }, $collection);
        expect(in_array('ia@example.com', $emails))->toBeTrue();
    });

    test('filter by email and name', function () {
        $u = new User();
        $u->setEmail('filterme@example.com')->setName('Filter Me')->setStatus(User::STATUS_ACTIVE);
        $this->em->persist($u);
        $this->em->flush();

        $response = $this->get(route('users.index', ['email' => 'filterme', 'perPage' => '']));
        $response->assertStatus(200);
        $collection = $response->viewData('collection');
        $emails = array_map(function($x) { return $x->getEmail(); }, $collection);
        expect(in_array('filterme@example.com', $emails))->toBeTrue();

        $response2 = $this->get(route('users.index', ['name' => 'Filter', 'perPage' => '']));
        $collection2 = $response2->viewData('collection');
        $names = array_map(function($x) { return $x->getName(); }, $collection2);
        expect(in_array('Filter Me', $names))->toBeTrue();
    });

    test('filter by role id', function () {
        $role = getRoleById(Role::ROLE_SALES);
        $u = createUserWithRole(Role::ROLE_SALES, 'sales@example.com', 'Sales User');

        $this->em->flush();

        $response = $this->get(route('users.index', ['role' => $role->getId(), 'perPage' => '']));
        $response->assertStatus(200);
        $collection = $response->viewData('collection');
        $emails = array_map(function($x) { return $x->getEmail(); }, $collection);
        expect(in_array('sales@example.com', $emails))->toBeTrue();
    });

    test('pagination returns LengthAwarePaginator and total count', function () {
        for ($i = 0; $i < 12; $i++) {
            $nu = new User();
            $nu->setEmail("u{$i}@example.com")->setName("User {$i}")->setStatus(User::STATUS_ACTIVE);
            $this->em->persist($nu);
        }
        $this->em->flush();

        $response = $this->get(route('users.index', ['perPage' => 5, 'page' => 1]));
        $response->assertStatus(200);
        $collection = $response->viewData('collection');
        expect($collection)->toBeInstanceOf(Illuminate\Pagination\LengthAwarePaginator::class);
        expect($collection->total())->toBeGreaterThanOrEqual(12);
        expect($collection->perPage())->toBe(5);
    });
});
