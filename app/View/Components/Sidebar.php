<?php

namespace App\View\Components;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Suppliers;
use App\Models\Barang;
use App\Models\Penerimaan;
use App\Models\Pembelian;
use App\Models\Gudang;
use App\Models\Departemen;
use App\Models\Akun;
use App\Models\User;
use App\Models\Pemakaian;
use App\Models\Stok;
use App\Models\Jurnal;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
    //     $userCount = User::count();
    //     view()->share('userCount',$userCount);
        
    //     $RoleCount = Role::count();
    //     view()->share('RoleCount',$RoleCount);
        
    //     $PermissionCount = Permission::count();
    //     view()->share('PermissionCount',$PermissionCount);
        
    //     $CategoryCount = Category::count();
    //     view()->share('CategoryCount',$CategoryCount);
        
    //     $SubCategoryCount = SubCategory::count();
    //     view()->share('SubCategoryCount',$SubCategoryCount);
        
    //     $CollectionCount = Collection::count();
    //     view()->share('CollectionCount',$CollectionCount);
        
    //     $ProductCount = Product::count();
    //     view()->share('ProductCount',$ProductCount);

    //     $SupplierCount = Suppliers::count();
    //     view()->share('SupplierCount',$SupplierCount);
        
    //     $BarangCount = Barang::count();
    //     view()->share('BarangCount',$BarangCount);

    //     $PenerimaanCount = Penerimaan::count();
    //     view()->share('PenerimaanCount',$PenerimaanCount);

        // $PembelianCount = Pembelian::count();
        // view()->share('PembelianCount',$PembelianCount);

    //     $GudangCount = Gudang::count();
    //     view()->share('GudangCount',$GudangCount);

    //     $DepartemenCount = Departemen::count();
    //     view()->share('DepartemenCount',$DepartemenCount);

    //     $AkunCount = Akun::count();
    //     view()->share('AkunCount',$AkunCount);

    //     $PemakaianCount = Pemakaian::count();
    //     view()->share('PemakaianCount',$PemakaianCount);

    //     $StokCount = Stok::count();
    //     view()->share('StokCount',$StokCount);

    //     $JurnalCount = Jurnal::count();
    //     view()->share('JurnalCount',$JurnalCount);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sidebar');
    }
}
