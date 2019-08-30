@extends('layouts.dashboard')

@section('css-ext')
<!-- DataTables -->
<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap4.css">
@endsection
@section('content')
<title>Status | BON DG</title>
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Status BON DG</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Status</li>
                            <li class="breadcrumb-item active">Gangguan</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <!-- general form elements -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fa fa-ban"></i> Peringatan!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>                            
                        @endif
                        @include('layouts.alert')
                        <div class="alert bg-white color-palette pb-0"> 
                            <div class="row" style="padding-bottom: 0">
                                <div class="col-md-1">
                                      <label>Filter:</label>  
                                </div>
                                <div class="col-md-9">                                    
                                    <form action="../statusbondg" method="POST">
                                    @csrf
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="form-group">                                                                                                                      
                                                    <label>Range Tanggal:</label> 
                                                </div>   
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">                                                                                                                      
                                                    <input class="form-control" type="date" name='datefrom' value="{{$datefrom}}">
                                                </div>                                 
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">                                                                                                                      
                                                    <input class="form-control" type="date" name="datetill" value="{{$datetill}}">
                                                </div>                                 
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">                                                                                                                      
                                                    <label>Status:</label> 
                                                </div>   
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">                                     
                                                    <select class="form-control select2" style="width: 100%;" name="status">
                                                        <option selected> {{$status}}</option>
                                                        <option>Semua Status</option>
                                                        <option>Laporan</option>
                                                        <option>Cetak PK</option>
                                                        <option>Pengiriman WO</option>
                                                        <option>Terpasang</option>
                                                        <option>Pengajuan Batal</option>
                                                        <option>Batal</option>
                                                        <option>Remaja</option>
                                                    </select>
                                                </div> 
                                            </div>
                                            <div class="col-md-2">                                         
                                                <button type="submit" class="btn btn-block btn-outline-info"><i class="fa fa-search"></i> Filter</button>
                                            </div>
                                        </div>
                                    </form>                                    
                                </div>
                                <div class="col-md-2">
                                    <form action="../downloadbondg" method="POST">
                                        @csrf
                                        <input class="form-control" type="date" name='datefrom' value="{{$datefrom}}" hidden>
                                        <input class="form-control" type="date" name="datetill" value="{{$datetill}}" hidden>
                                        <input class="form-control" type="text" name="status" value="{{$status}}" hidden>
                                        <button type="submit" class="btn btn-block btn-outline-success"><i class="fa fa-download"></i> Download</button>
                                    </form>                                      
                                    
                                </div>

                            </div>
                        </div>
                        <div class="card">                           
                            <!-- /.card-header -->
                            <div class="card-body" style="overflow-x: auto">                                
                                <table id="example1" class="table table-bordered table-striped" >
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">No.</th>
                                            <th>Tgl. Laporan</th>
                                            <th>No. DG/No. Laporan lain</th>
                                            <th>Nama</th>
                                            <th>ID Pelanggan</th>
                                            <th>Status</th>
                                            <th style="width: 10%;">Waktu Pengerjaan</th>
                                            <th>Pilihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bondg as $data)
                                        <tr>
                                            <td style="width: 5%;">{{$no++}}.</td>
                                            <td><?php echo Carbon\Carbon::createFromDate($data->tgldg)->format('d-m-Y');?></td>
                                            <td>
                                                {{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}                                             
                                            </td>
                                            <td>{{$data->namapel}}</td>
                                            <td>{{$data->idpel}}</td>
                                            <td>{{$data->status}}</td>
                                            <td>
                                                {{$data->waktupengerjaan}} hari
                                            </td>
                                            <td style="width: 10%" class="text-center">
                                                <div class="btn-group">
                                                <form action = "../detail-bondg" method="POST">
                                                @csrf
                                                    <input type="text" value="{{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}" name="id" hidden>
                                            
                                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                                                </form>
                                                    <button type="button" class="btn btn-warning btn-sm"data-toggle="modal" data-target="#modalEdit{{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}"><i class="fa fa-edit"></i></button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalDelete{{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade bd-example-modal-lg" id="modalEdit{{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}"tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <form action="{{url('/edit-bondg/'.str_pad($data->nodg, 8, '0', STR_PAD_LEFT))}}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Ubah BON DG</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Posko</label>
                                                                    <select class="form-control select2" style="width: 100%;" name="posko" required>
                                                                        <option selected="selected">{{$data->posko}}</option>
                                                                        <option>Labuan</option>
                                                                        <option>Menes</option>
                                                                        <option>Panimbang</option>
                                                                        <option>Cibaliung</option>
                                                                        <option>Sumur</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Nomor DG</label>
                                                                        <input type="text" class="form-control" id="exampleInputPassword1" value="{{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}" placeholder="Masukkan nomor DG..." name="nodg_new" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Nama pelapor</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->namapel}}" placeholder="Masukkan nama pelapor..." name="namapel" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">ID pelanggan</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->idpel}}" placeholder="Masukkan id pelanggan..." name="idpel" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Alamat</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->alamat}}" placeholder="Masukkan id alamat..." name="alamat" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Keluhan</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->jenisGangguan->nama_gangguan}}" disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Merk KWH meter lama</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->kwhmeterlama_merk}}" placeholder="Masukkan merk KWH meter lama..." name="merk">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Type KWH meter lama</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->kwhmeterlama_type}}" placeholder="Masukkan type KWH meter lama..." name="type">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Gardu</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->gardu}}" placeholder="Masukkan gardu..." name="gardu" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Tarif</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->tarif}}" placeholder="Masukkan tarif..." name="tarif" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Daya</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->daya}}" placeholder="Masukkan daya..." name="daya" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">No. HP</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->nohp}}" placeholder="Masukkan no. hp..." name="nohp" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">No. meter lama</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->nometerlama}}" placeholder="Masukkan no. meter lama..." name="nometerlama" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Perbaikan</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->jenisPerbaikan->nama_perbaikan}}" placeholder="Masukkan perbaikan..." disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Tahun buat KWH meter lama</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->kwhmeterlama_th}}" placeholder="Masukkan tahun KWH meter lama..." name="tahun">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleInputPassword1">Sisa KWH meter lama</label>
                                                                    <input type="text" class="form-control" id="exampleInputPassword1" value="{{$data->kwhmeterlama_sisakwh}}" placeholder="Masukkan sisa KWH meter lama..." name="sisakwh">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-warning">Ubah</button>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="modalDelete{{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Hapus BON DG</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus BON DG ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                        <form action = "../hapus-bondg" method="POST">
                                                        @csrf
                                                            <input type="text" value="{{str_pad($data->nodg, 8, '0', STR_PAD_LEFT)}}" name="id" hidden>
                                                    
                                                            <button type="submit" class="btn btn-danger">Ya</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                    </div>
                    <!-- /.card -->
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('js-ext')
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
    $(function () {
    $('#example1').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": true
    });
    });
</script>
@endsection