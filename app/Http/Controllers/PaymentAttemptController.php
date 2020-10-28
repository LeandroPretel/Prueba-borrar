<?php

namespace App\Http\Controllers;

use App\Order;
use App\PaymentAttempt;
use Savitar\Auth\Traits\Authorization;
use Savitar\DataGrid\Traits\DataGrid;

class PaymentAttemptController extends Controller
{
    use DataGrid;
    use Authorization;

    /**
     * ArtistController constructor.
     */
    public function __construct()
    {
        $this->initAuthorization(Order::class);
        $this->configureDataGrid([
            'modelClass' => PaymentAttempt::class,
            'dataGridTitle' => 'Intentos de pago',
            'defaultOrderBy' => 'updatedAt',
            'defaultSortOrder' => 'desc',
        ]);
    }
}
