<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CSJ - Mantenaince Mode</title>
    <link rel="shortcut icon" href="/img/favicon/avatar2.png" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }

        .main {
            width: 100%;
            max-width: 880px;
            border-radius: 12px;
            padding: 2.25rem;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.2);
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 3rem;
            align-items: stretch;
        }

        .left {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .right {
            text-align: center;
        }
    </style>
</head>

<body>


    <div class="main">
        <section class="left">

            <img src="/img/maintenance/_blue.png" width="10%">
            <div>
                <h1>Sitio en mantenimiento</h1>

                <p>Disculpa las molestias. Estamos realizando tareas de mantenimiento para mejorar el servicio. Volveremos muy pronto.</p>
            </div>
            <p class="small">
                Si necesitas soporte urgente, contacta con <a href="mailto:adminikt@fpsanturtzilh.eus">adminikt@fpsanturtzilh.eus</a>
            </p>
        </section>
        <aside class="right">
            <img src="/img/maintenance/the_IT_Crowd_small.png" width="100%">
        </aside>
    </div>

</body>

</html>