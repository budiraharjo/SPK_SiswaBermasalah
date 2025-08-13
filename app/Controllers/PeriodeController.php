<?php
namespace App\Controllers;

use App\Models\Periode;

class PeriodeController
{
    protected $periode;

    public function __construct()
    {
        $this->periode = new Periode();
    }

    public function index()
    {
        $data['periode'] = $this->periode->getAll();
        extract($data);
        require __DIR__ . '/../Views/dashboard/periode.php';
    }

    public function store()
    {
        $periode = $_POST['periode'];
        $this->periode->store($periode);
        header('Location: /dashboard/periode');
        exit;
    }

    public function update($id)
    {
        $periode = $_POST['periode'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $this->periode->update($id, $periode, $is_active);
        header('Location: /dashboard/periode');
        exit;
    }

    public function delete($id)
    {
        $this->periode->delete($id);
        header('Location: /dashboard/periode');
        exit;
    }
}
