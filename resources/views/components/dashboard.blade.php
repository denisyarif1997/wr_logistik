 {{-- dashboard css --}}
 <style>
    .dashboard-box {
        border-radius: 16px;
        padding: 20px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .dashboard-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .dashboard-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 40px;
        opacity: 0.2;
    }

    .dashboard-inner h3 {
        font-size: 36px;
        font-weight: bold;
        margin: 0;
    }

    .dashboard-inner p {
        margin: 5px 0 0;
        font-size: 18px;
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #218838);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }

    .small-box-footer {
        color: white !important;
        text-decoration: underline;
        font-weight: bold;
    }
</style>

<div class="row">
    @role('admin')
        <div class="col-lg-3 col-6 mb-4">
            <div class="dashboard-box bg-gradient-info">
                <div class="dashboard-inner">
                    <h3>{{ $user }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="dashboard-icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('admin.user.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-4">
            <div class="dashboard-box bg-gradient-success">
                <div class="dashboard-inner">
                    <h3>{{ $category }}</h3>
                    <p>Total Categories</p>
                </div>
                <div class="dashboard-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <a href="{{ route('admin.category.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-4">
            <div class="dashboard-box bg-gradient-primary">
                <div class="dashboard-inner">
                    <h3>{{ $pembelian }}</h3>
                    <p>Total Purchase Order</p>
                </div>
                <div class="dashboard-icon">
                    <i class="fas fa-th"></i>
                </div>
                <a href="{{ route('admin.pembelian.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6 mb-4">
            <div class="dashboard-box bg-gradient-secondary">
                <div class="dashboard-inner">
                    <h3>{{ $penerimaan }}</h3>
                    <p>Total Penerimaan</p>
                </div>
                <div class="dashboard-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <a href="{{ route('admin.penerimaan.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
         <div class="col-lg-3 col-6 mb-4">
            <div class="dashboard-box bg-gradient-secondary">
                <div class="dashboard-inner">
                    <h3>{{ $detailPemakaian }}</h3>
                    <p>Total Barang Dipakai</p>
                </div>
                <div class="dashboard-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <a href="{{ route('admin.pemakaian.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    @endrole
</div>
