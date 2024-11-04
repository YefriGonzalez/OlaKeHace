# Manual Técnico

## Introduccion
Este proyecto tiene como objetivo suplir las necesidades del cliente para su sistema OlaKeHace,
administrar publicaciones, aprobarlas, rechazarlas y un portal para poder marcar asistencias a eventos.
## Visión
| Actor | Objetivo |
| ------------ | ------------ |
| Administrador | Aprobar, rechazar publicaciones, aprobar u omitir baneos| 
| Usuario | Crear publicaciones, asistir a eventos |



## Herramientas Utilizadas
| Tecnología | Versión |
| ------------ | ------------ |
|VS Code | 1.9.2  |
|Git | 2.34.1|
|Laravel | 11|
|MySQL | 8.0.39|
|HTML | 5|
|CSS | 3|



## DIAGRAMA DE TABLAS
![alt text](<diagramas/Diagrama de tablas.png>)


## Diagrama de paquetes
![alt text](<diagramas/Diagrama Paquetes TEO2.png>)

## Diagrama de Despliegue
![alt text](<diagramas/Diagrama Despligue TEO2.png>)

## Diagrama de secuencia
![alt text](<diagramas/Diagrama secuencia Proyecto 2 TEO.png>)
## Estructura del proyecto
```
OlaKeHace
├─ .gitignore
├─ Manual Tecnico.md
├─ Manual de Usuario.md
├─ app
│  ├─ Console
│  │  └─ Kernel.php
│  ├─ Events
│  │  └─ RealTimeMessag.php
│  ├─ Exceptions
│  │  └─ Handler.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Controller.php
│  │  │  ├─ EventController.php
│  │  │  ├─ HomeController.php
│  │  │  ├─ NotificationController.php
│  │  │  ├─ PermisoController.php
│  │  │  ├─ PublicacionController.php
│  │  │  ├─ ReporteController.php
│  │  │  ├─ RolController.php
│  │  │  ├─ User
│  │  │  │  └─ LoginController.php
│  │  │  └─ UserController.php
│  │  ├─ Kernel.php
│  │  └─ Middleware
│  │     ├─ Authenticate.php
│  │     ├─ CheckAdmin.php
│  │     ├─ EncryptCookies.php
│  │     ├─ PreventRequestsDuringMaintenance.php
│  │     ├─ RedirectIfAuthenticated.php
│  │     ├─ TrimStrings.php
│  │     ├─ TrustHosts.php
│  │     ├─ TrustProxies.php
│  │     ├─ ValidateSignature.php
│  │     └─ VerifyCsrfToken.php
│  ├─ Models
│  │  ├─ Event.php
│  │  ├─ Notification.php
│  │  ├─ Permiso.php
│  │  ├─ Publicacion.php
│  │  ├─ Reporte.php
│  │  ├─ Rol.php
│  │  └─ User.php
│  └─ Providers
│     ├─ AppServiceProvider.php
│     ├─ AuthServiceProvider.php
│     ├─ BroadcastServiceProvider.php
│     ├─ EventServiceProvider.php
│     └─ RouteServiceProvider.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  └─ cache
│     ├─ .gitignore
│     ├─ packages.php
│     └─ services.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ broadcasting.php
│  ├─ cache.php
│  ├─ cors.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ hashing.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ sanctum.php
│  ├─ services.php
│  ├─ session.php
│  └─ view.php
├─ database
│  ├─ .gitignore
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0000_00_00_000000_create_websockets_statistics_entries_table.php
│  │  ├─ 2024_10_14_034110_create_Evento_table.php
│  │  ├─ 2024_10_14_034110_create_Notificacion_table.php
│  │  ├─ 2024_10_14_034110_create_Permiso_table.php
│  │  ├─ 2024_10_14_034110_create_Publicacion_table.php
│  │  ├─ 2024_10_14_034110_create_RolPermiso_table.php
│  │  ├─ 2024_10_14_034110_create_Rol_table.php
│  │  ├─ 2024_10_14_034110_create_Usuario_table.php
│  │  ├─ 2024_10_14_034110_create_estado_table.php
│  │  ├─ 2024_10_14_034110_create_reporte_table.php
│  │  ├─ 2024_10_14_034113_add_foreign_keys_to_Evento_table.php
│  │  ├─ 2024_10_14_034113_add_foreign_keys_to_Notificacion_table.php
│  │  ├─ 2024_10_14_034113_add_foreign_keys_to_Publicacion_table.php
│  │  ├─ 2024_10_14_034113_add_foreign_keys_to_RolPermiso_table.php
│  │  ├─ 2024_10_14_034113_add_foreign_keys_to_Usuario_table.php
│  │  ├─ 2024_10_15_045016_add_foreign_publicion_id_to_reporte_table.php
│  │  ├─ 2024_10_15_045035_add_foreign_usuario_id_to_reporte_table.php
│  │  └─ 2024_10_28_035253_create_notifications_table.php
│  └─ seeders
│     └─ DatabaseSeeder.php
├─ diagramas
│  ├─ Diagrama Despligue TEO2.png
│  ├─ Diagrama Paquetes TEO2.png
│  ├─ Diagrama de tablas.png
│  └─ Diagrama secuencia Proyecto 2 TEO.png
└─ vite.config.js
```

## Instalacion

### 1. Instalación
1. **Actualizar el sistema:**
   ```bash
   sudo apt update
   sudo apt upgrade
2. **Instalar php y php-mysql:**
    ```bash
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    sudo apt install php libapache2-mod-php php-mysql
3. **Instalar mysql**
    ```bash
    sudo apt install mysql-server
4. **Iniciar servicio de mysql**        
    ```bash
    sudo systemctl start mysql
5. **Configuar mysql**
    ```bash
    sudo systemctl start mysql
    Pedira la contraseña del root, recordar esta contraseña

### 2. Ejecucion del programa
1. **Instalar dependencias:**
    ```bash
    composer install
2. **Generar dump-autoload:**
    ```bash
    composer dump-autoload
3. **Ejecutar el programa:**
    ```bash
    php artisan serve
    npm run dev

