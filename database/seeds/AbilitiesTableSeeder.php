<?php

use Illuminate\Database\Seeder;
use Savitar\Auth\SavitarAbility;

class AbilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->create('stats', 'Estadísticas', 'Estadísticas', 'Permiso para ver las estadísticas');
        $this->create('configuration', 'Configuración', 'Configuración', 'Permiso para configurar la cuenta');

        // Discounts
        $this->create('discounts', 'Descuentos y promociones', 'Descuentos', 'Permiso para ver los descuentos y promociones');
        $this->create('create-discounts', 'Crear descuentos y promociones', 'Descuentos', 'Permiso para crear los descuentos y promociones');
        $this->create('edit-discounts', 'Editar descuentos y promociones', 'Descuentos', 'Permiso para editar los descuentos y promociones');
        $this->create('delete-discounts', 'Eliminar descuentos y promociones', 'Descuentos', 'Permiso para eliminar descuentos y promociones');
        $this->create('restore-discounts', 'Restaurar descuentos y promociones', 'Descuentos', 'Permiso para restaurar descuentos y promociones');
        $this->create('delete-discounts-permanently', 'Eliminar descuentos y promociones permanentemente', 'Descuentos', 'Permiso para eliminar descuentos y promociones permanentemente');

        // Place
        $this->create('places', 'Recintos', 'Recintos', 'Permiso para ver los lugares de celebración de eventos');
        $this->create('create-places', 'Crear recintos', 'Recintos', 'Permiso para crear los recintos de celebración de eventos');
        $this->create('edit-places', 'Editar recintos', 'Recintos', 'Permiso para editar los recintos de celebración de eventos');
        $this->create('delete-places', 'Eliminar recintos', 'Recintos', 'Permiso para eliminar recintos de celebración de eventos');
        $this->create('restore-places', 'Restaurar recintos', 'Recintos', 'Permiso para restaurar recintos de celebración de eventos');
        $this->create('delete-places-permanently', 'Eliminar recintos permanentemente', 'Recintos', 'Permiso para eliminar recintos de celebración de eventos permanentemente');

        // Space
        $this->create('spaces', 'Aforos', 'Aforos', 'Permiso para ver los aforos de celebración de eventos de los recintos');
        $this->create('create-spaces', 'Crear aforos', 'Aforos', 'Permiso para crear los aforos de celebración de eventos de los recintos');
        $this->create('edit-spaces', 'Editar aforos', 'Aforos', 'Permiso para editar los aforos de celebración de eventos de los recintos');
        $this->create('delete-spaces', 'Eliminar aforos', 'Aforos', 'Permiso para eliminar aforos de celebración de eventos de los lugares recintos');
        $this->create('restore-spaces', 'Restaurar aforos', 'Aforos', 'Permiso para restaurar aforos de celebración de eventos de los lugares recintos');
        $this->create('delete-spaces-permanently', 'Eliminar aforos permanentemente', 'Aforos', 'Permiso para eliminar aforos de celebración de eventos de los recintos permanentemente');

        // Area
        $this->create('areas', 'Áreas', 'Áreas', 'Permiso para ver las areas de cada espacio');
        $this->create('create-areas', 'Crear áreas', 'Áreas', 'Permiso para crear areas para un espacio');
        $this->create('edit-areas', 'Editar áreas', 'Áreas', 'Permiso para editar las áreas de un recinto');
        $this->create('delete-areas', 'Eliminar áreas', 'Áreas', 'Permiso para eliminar áreas de un recinto');
        $this->create('restore-areas', 'Restaurar áreas', 'Áreas', 'Permiso para restaurar áreas de un recinto');
        $this->create('delete-areas-permanently', 'Eliminar áreas permanentemente', 'Áreas', 'Permiso para eliminar áreas de un recinto permanentemente');

        // Door
        $this->create('doors', 'Puertas', 'Puertas', 'Permiso para ver las puertas de cada espacio');
        $this->create('create-doors', 'Crear puertas', 'Puertas', 'Permiso para crear puertas para un espacio');
        $this->create('edit-doors', 'Editar puertas', 'Puertas', 'Permiso para editar las puertas de un recinto');
        $this->create('delete-doors', 'Eliminar puertas', 'Puertas', 'Permiso para eliminar puertas de un recinto');
        $this->create('restore-doors', 'Restaurar puertas', 'Puertas', 'Permiso para restaurar puertas de un recinto');
        $this->create('delete-doors-permanently', 'Eliminar puertas permanentemente', 'Puertas', 'Permiso para eliminar puertas de un recinto permanentemente');

        // Artist
        $this->create('artists', 'Artistas', 'Artistas', 'Permiso para ver los artistas');
        $this->create('create-artists', 'Crear artistas', 'Artistas', 'Permiso para crear nuevos artistas');
        $this->create('edit-artists', 'Editar artistas', 'Artistas', 'Permiso para editar artistas');
        $this->create('delete-artists', 'Eliminar artistas', 'Artistas', 'Permiso para eliminar artistas');
        $this->create('restore-artists', 'Restaurar artistas', 'Artistas', 'Permiso para restaurar artistas eliminados');
        $this->create('delete-artists-permanently', 'Eliminar artistas permanentemente', 'Artistas', 'Permiso para eliminar artistas permanentemente');

        // Show
        $this->create('shows', 'Eventos', 'Espectáculos', 'Permiso para ver los eventos');
        $this->create('create-shows', 'Crear eventos', 'Espectáculos', 'Permiso para crear nuevos eventos');
        $this->create('edit-shows', 'Editar eventos', 'Espectáculos', 'Permiso para editar eventos');
        $this->create('delete-shows', 'Eliminar eventos', 'Espectáculos', 'Permiso para eliminar eventos');
        $this->create('restore-shows', 'Restaurar eventos', 'Espectáculos', 'Permiso para restaurar eventos eliminados');
        $this->create('delete-shows-permanently', 'Eliminar eventos permanentemente', ' Espectáculos', 'Permiso para eliminar eventos permanentemente');

        // ShowTemplate
        $this->create('show-templates', 'Espectáculos', 'Espectáculos', 'Permiso para ver las espectáculos');
        $this->create('create-show-templates', 'Crear espectáculos', 'Espectáculos', 'Permiso para crear nuevas espectáculos');
        $this->create('edit-show-templates', 'Editar espectáculos', 'Espectáculos', 'Permiso para editar espectáculos');
        $this->create('delete-show-templates', 'Eliminar espectáculos', 'Espectáculos', 'Permiso para eliminar espectáculos');
        $this->create('restore-show-templates', 'Restaurar espectáculos', 'Espectáculos', 'Permiso para restaurar espectáculos eliminadas');
        $this->create('delete-show-templates-permanently', 'Eliminar espectáculos permanentemente', ' Espectáculos', 'Permiso para eliminar espectáculos permanentemente');

        // ShowCategory
        $this->create('show-categories', 'Categorías de eventos', 'Espectáculos', 'Permiso para ver las categorías de eventos');
        $this->create('create-show-categories', 'Crear categorías de eventos', 'Espectáculos', 'Permiso para crear nuevas categorías de eventos');
        $this->create('edit-show-categories', 'Editar categorías de eventos', 'Espectáculos', 'Permiso para editar categorías de eventos');
        $this->create('delete-show-categories', 'Eliminar categorías de eventos', 'Espectáculos', 'Permiso para eliminar categorías de eventos');
        $this->create('restore-show-categories', 'Restaurar categorías de eventos', 'Espectáculos', 'Permiso para restaurar categorías de eventos eliminadas');
        $this->create('delete-show-categories-permanently', 'Eliminar categorías de eventos permanentemente', 'Espectáculos', 'Permiso para eliminar categorías de eventos permanentemente');

        // Fare
        $this->create('fares', 'Tarifas', 'Permiso para ver las tarifas');
        $this->create('create-fares', 'Crear tarifas', 'Tarifas', 'Permiso para crear nuevas tarifas');
        $this->create('edit-fares', 'Editar tarifas', 'Tarifas', 'Permiso para editar tarifas');
        $this->create('delete-fares', 'Eliminar tarifas', 'Tarifas', 'Permiso para eliminar tarifas');
        $this->create('restore-fares', 'Restaurar tarifas', 'Tarifas', 'Permiso para restaurar tarifas eliminadas');
        $this->create('delete-fares-permanently', 'Eliminar tarifas permanentemente', 'Tarifas', 'Permiso para eliminar tarifas permanentemente');

        // Session
        $this->create('sessions', 'Sesiones', 'Sesiones', 'Permiso para ver las sesiones');
        $this->create('create-sessions', 'Crear sesiones', 'Sesiones', 'Permiso para crear nuevas sesiones');
        $this->create('edit-sessions', 'Editar sesiones', 'Sesiones', 'Permiso para editar sesiones');
        $this->create('delete-sessions', 'Eliminar sesiones', 'Sesiones', 'Permiso para eliminar sesiones');
        $this->create('restore-sessions', 'Restaurar sesiones', 'Sesiones', 'Permiso para restaurar sesiones eliminadas');
        $this->create('delete-sessions-permanently', 'Eliminar sesiones permanentemente', 'Sesiones', 'Permiso para eliminar sesiones permanentemente');

        // Ticket
        $this->create('tickets', 'Entradas', 'Compras', 'Permiso para ver las entradas');
        $this->create('create-tickets', 'Crear entradas', 'Compras', 'Permiso para crear nuevas entradas');
        $this->create('edit-tickets', 'Editar entradas', 'Compras', 'Permiso para editar entradas');
        $this->create('delete-tickets', 'Eliminar entradas', 'Compras', 'Permiso para eliminar entradas');
        $this->create('restore-tickets', 'Restaurar entradas', 'Compras', 'Permiso para restaurar entradas eliminadas');
        $this->create('delete-tickets-permanently', 'Eliminar entradas permanentemente', 'Compras', 'Permiso para eliminar entradas permanentemente');

        // Order
        $this->create('orders', 'Compras', 'Compras', 'Permiso para ver las compras');
        $this->create('create-orders', 'Crear compras', 'Compras', 'Permiso para crear nuevas compras');
        $this->create('edit-orders', 'Editar compras', 'Compras', 'Permiso para editar compras');
        $this->create('delete-orders', 'Eliminar compras', 'Compras', 'Permiso para eliminar compras');
        $this->create('restore-orders', 'Restaurar compras', 'Compras', 'Permiso para restaurar compras eliminadas');
        $this->create('delete-orders-permanently', 'Eliminar compras permanentemente', 'Compras', 'Permiso para eliminar compras permanentemente');

        // Order returns
        $this->create('order-returns-with-distribution', 'Devoluciones con distribucíón', 'Devoluciones', 'Permiso para realizar devoluciones con gastos de distribución');
        $this->create('order-returns', 'Devoluciones', 'Devoluciones', 'Permiso para ver las devoluciones');
        $this->create('create-order-returns', 'Crear devoluciones', 'Devoluciones', 'Permiso para crear nuevas devoluciones');
        $this->create('edit-order-returns', 'Editar devoluciones', 'Devoluciones', 'Permiso para editar devoluciones');
        $this->create('delete-order-returns', 'Eliminar devoluciones', 'Devoluciones', 'Permiso para eliminar devoluciones');
        $this->create('restore-order-returns', 'Restaurar devoluciones', 'Devoluciones', 'Permiso para restaurar devoluciones eliminadas');
        $this->create('delete-order-returns-permanently', 'Eliminar devoluciones permanentemente', 'Devoluciones', 'Permiso para eliminar devoluciones permanentemente');

        // Client
        $this->create('clients', 'Clientes', 'Clientes', 'Permiso para ver los clientes');
        $this->create('create-clients', 'Crear clientes', 'Clientes', 'Permiso para crear nuevos clientes');
        $this->create('edit-clients', 'Editar clientes', 'Clientes', 'Permiso para editar clientes');
        $this->create('delete-clients', 'Eliminar clientes', 'Clientes', 'Permiso para eliminar clientes');
        $this->create('restore-clients', 'Restaurar clientes', 'Clientes', 'Permiso para restaurar clientes eliminados');
        $this->create('delete-clients-permanently', 'Eliminar clientes permanentemente', 'Clientes', 'Permiso para eliminar clientes permanentemente');

        // Consultation
        $this->create('consultations', 'Consultas', 'Consultas', 'Permiso para ver las consultas');
        $this->create('create-consultations', 'Crear consultas', 'Consultas', 'Permiso para crear nuevas consultas');
        $this->create('edit-consultations', 'Editar consultas', 'Consultas', 'Permiso para editar consultas');
        $this->create('delete-consultations', 'Eliminar consultas', 'Consultas', 'Permiso para eliminar consultas');
        $this->create('restore-consultations', 'Restaurar consultas', 'Consultas', 'Permiso para restaurar consultas eliminados');
        $this->create('delete-consultations-permanently', 'Eliminar consultas clientes permanentemente', 'Consultas', 'Permiso para eliminar consultas permanentemente');

        // Ticket offices
        $this->create('points-of-sale', 'Puntos de venta', 'Puntos de venta', 'Permiso para ver los puntos de venta');
        $this->create('create-points-of-sale', 'Crear puntos de venta', 'Puntos de venta', 'Permiso para crear nuevos puntos de venta');
        $this->create('edit-points-of-sale', 'Editar puntos de venta', 'Puntos de venta', 'Permiso para editar puntos de venta');
        $this->create('delete-points-of-sale', 'Eliminar puntos de venta', 'Puntos de venta', 'Permiso para eliminar puntos de venta');
        $this->create('restore-points-of-sale', 'Restaurar puntos de venta', 'Puntos de venta', 'Permiso para restaurar puntos de venta eliminados');
        $this->create('delete-points-of-sale-permanently', 'Eliminar puntos de venta permanentemente', 'Puntos de venta', 'Permiso para eliminar puntos de venta permanentemente');

        // Ticket office users
        $this->create('point-of-sale-users', 'Usuarios de punto de venta', 'Clientes', 'Permiso para ver los usuarios de punto de venta');
        $this->create('create-point-of-sale-users', 'Crear usuarios de punto de venta', 'Clientes', 'Permiso para crear nuevos usuarios de punto de venta');
        $this->create('edit-point-of-sale-users', 'Editar usuarios de punto de venta', 'Clientes', 'Permiso para editar usuarios de punto de venta');
        $this->create('delete-point-of-sale-users', 'Eliminar usuarios de punto de venta', 'Clientes', 'Permiso para eliminar usuarios de punto de venta');
        $this->create('restore-point-of-sale-users', 'Restaurar usuarios de punto de venta', 'Clientes', 'Permiso para restaurar usuarios de punto de venta eliminados');
        $this->create('delete-point-of-sale-users-permanently', 'Eliminar usuarios de punto de venta permanentemente', 'Clientes', 'Permiso para eliminar usuarios de punto de venta permanentemente');

        // Ticket sales
        $this->create('ticket-sales', 'Ventas de entradas', 'Puntos de venta', 'Permiso para ver las ventas de entradas');
        $this->create('create-ticket-sales', 'Crear ventas de entradas', 'Puntos de venta', 'Permiso para crear nuevas ventas de entradas');
        $this->create('edit-ticket-sales', 'Editar ventas de entradas', 'Puntos de venta', 'Permiso para editar ventas de entradas');
        $this->create('delete-ticket-sales', 'Eliminar ventas de entradas', 'Puntos de venta', 'Permiso para eliminar ventas de entradas');
        $this->create('restore-ticket-sales', 'Restaurar ventas de entradas', 'Puntos de venta', 'Permiso para restaurar ventas de entradas');
        $this->create('delete-ticket-sales-permanently', 'Eliminar ventas de entradas permanentemente', 'Puntos de venta', 'Permiso para eliminar ventas de entradas permanentemente');

        // Ticket collections
        $this->create('ticket-collections', 'Recogidas de entradas', 'Puntos de venta', 'Permiso para ver las recogidas de entradas');
        $this->create('create-ticket-collections', 'Crear recogidas de entradas', 'Puntos de venta', 'Permiso para crear nuevas recogidas de entradas');
        $this->create('edit-ticket-collections', 'Editar recogidas de entradas', 'Puntos de venta', 'Permiso para editar recogidas de entradas');
        $this->create('delete-ticket-collections', 'Eliminar recogidas de entradas', 'Puntos de venta', 'Permiso para eliminar recogidas de entradas');
        $this->create('restore-ticket-collections', 'Restaurar recogidas de entradas', 'Puntos de venta', 'Permiso para restaurar recogidas de entradas');
        $this->create('delete-ticket-collections-permanently', 'Eliminar recogidas de entradas permanentemente', 'Puntos de venta', 'Permiso para eliminar recogidas de entradas permanentemente');

        // Ticket office mode
        $this->create('ticket-office', 'Modo taquilla', 'Puntos de venta', 'Permiso para ver el modo taquilla');
//        $this->create('create-ticket-sales', 'Crear ventas de entradas', 'Puntos de venta', 'Permiso para crear nuevas ventas de entradas');
//        $this->create('edit-ticket-sales', 'Editar usuarios de punto de venta', 'Puntos de venta', 'Permiso para editar ventas de entradas');
//        $this->create('delete-ticket-sales', 'Eliminar usuarios de punto de venta', 'Puntos de venta', 'Permiso para eliminar ventas de entradas');
//        $this->create('restore-ticket-sales', 'Restaurar usuarios de punto de venta', 'Puntos de venta', 'Permiso para restaurar ventas de entradas');
//        $this->create('delete-ticket-sales-permanently', 'Eliminar usuarios de punto de venta permanentemente', 'Puntos de venta', 'Permiso para eliminar ventas de entradas permanentemente');

        // Access Control
        $this->create('accesses', 'Control de accesos', 'Control de accesos', 'Permiso para ver los accesos');
        $this->create('create-accesses', 'Añadir accesos', 'Control de accesos', 'Permiso para crear nuevos accesos');
        $this->create('edit-accesses', 'Editar accesos', 'Control de accesos', 'Permiso para editar accesos');
        $this->create('delete-accesses', 'Eliminar accesos', 'Control de accesos', 'Permiso para eliminar accesos');

        // Ticket vouchers and seasons (bonos y abonos)
        $this->create('ticket-seasons', 'Abonos', 'Abonos', 'Permiso para ver los abonos');
        $this->create('create-ticket-seasons', 'Crear abonos', 'Abonos', 'Permiso para crear abonos');
        $this->create('edit-ticket-seasons', 'Editar abonos', 'Abonos', 'Permiso para editar abonos');
        $this->create('delete-ticket-seasons', 'Eliminar abonos', 'Abonos', 'Permiso para eliminar abonos');
        $this->create('restore-ticket-seasons', 'Restaurar abonos', 'Abonos', 'Permiso para restaurar abonos');
        $this->create('delete-ticket-seasons-permanently', 'Eliminar abonos permanentemente', 'Abonos', 'Permiso para eliminar abonos permanentemente');

        $this->create('ticket-vouchers', 'Bonos', 'Bonos', 'Permiso para ver los bonos');
        $this->create('create-ticket-vouchers', 'Crear bonos', 'Bonos', 'Permiso para crear bonos');
        $this->create('edit-ticket-vouchers', 'Editar bonos', 'Bonos', 'Permiso para editar bonos');
        $this->create('delete-ticket-vouchers', 'Eliminar bonos', 'Bonos', 'Permiso para eliminar bonos');
        $this->create('restore-ticket-vouchers', 'Restaurar bonos', 'Bonos', 'Permiso para restaurar bonos');
        $this->create('delete-ticket-vouchers-permanently', 'Eliminar bonos permanentemente', 'Bonos', 'Permiso para eliminar bonos permanentemente');

        // Enterprises
        $this->create('enterprises', 'Empresas', 'Empresas', 'Permiso para ver las empresas');
        $this->create('create-enterprises', 'Crear empresas', 'Empresas', 'Permiso para crear empresas');
        $this->create('edit-enterprises', 'Editar empresas', 'Empresas', 'Permiso para editar empresas');
        $this->create('delete-enterprises', 'Eliminar empresas', 'Empresas', 'Permiso para eliminar empresas');
        $this->create('restore-enterprises', 'Restaurar empresas', 'Empresas', 'Permiso para restaurar empresas');
        $this->create('delete-enterprises-permanently', 'Eliminar empresas permanentemente', 'Empresas', 'Permiso para eliminar empresas permanentemente');
    }

    /**
     * @param string $slug
     * @param string $name
     * @param string|null $category
     * @param string|null $description
     */
    private function create(string $slug, string $name, ?string $category = null, ?string $description = null): void
    {
        /** @var SavitarAbility $ability */
        $ability = SavitarAbility::where('slug', $slug)->firstOrNew([]);
        $ability->id = \Illuminate\Support\Str::uuid()->toString();
        $ability->slug = $slug;
        $ability->name = $name;
        $ability->category = $category;
        $ability->description = $description;
        $ability->saveWithoutEvents();
    }
}
