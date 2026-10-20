<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Siswa;

class PengaduanController extends Controller
{
    private $model; private $siswaModel; private $kategoriModel;
    public function __construct(){Auth::requireLogin();$this->model=new Pengaduan();$this->siswaModel=new Siswa();$this->kategoriModel=new Kategori();}
    public function index(): void {
        if(Auth::isAdmin()) {$pengaduan=$this->model->all();} else {$pengaduan=$this->model->byNis((int)Auth::user()['nis']);}
        $this->view('pengaduan/index',['pengaduan'=>$pengaduan]);
    }
    public function create(): void {
        $error='';$old=[];$user=Auth::user();
        if($_SERVER['REQUEST_METHOD']==='POST'){
            if(!Csrf::verify($_POST['csrf_token']??null)) $error='Sesi form sudah kedaluwarsa, silakan coba lagi.';
            else {
                $nis=Auth::isSiswa()?(string)$user['nis']:trim($_POST['nis']??'');$idKategori=trim($_POST['id_kategori']??'');
                $data=['lokasi'=>trim($_POST['lokasi']??''),'ket'=>trim($_POST['ket']??'')];$old=array_merge(['nis'=>$nis,'id_kategori'=>$idKategori],$data);
                if($nis===''||$idKategori===''||$data['lokasi']===''||$data['ket']==='') $error='Semua field pengaduan wajib diisi.';
                elseif(!ctype_digit($nis)||!ctype_digit($idKategori)) $error='NIS dan kategori harus valid.';
                else {$data['nis']=(int)$nis;$data['id_kategori']=(int)$idKategori;if($this->model->create($data)){Flash::set('success','Pengaduan berhasil ditambahkan.');$this->redirect('pengaduan');}$error='Gagal menambahkan pengaduan.';}
            }
        }
        $this->view('pengaduan/form',['mode'=>'create','error'=>$error,'data'=>$old,'siswaList'=>Auth::isAdmin()?$this->siswaModel->all():[$this->siswaModel->find((int)$user['nis'])],'kategoriList'=>$this->kategoriModel->allAktif()]);
    }
    public function edit(): void { Auth::requireAdmin(); $id=(int)($_GET['id']??0);$pengaduan=$id>0?$this->model->find($id):null;if(!$pengaduan){Flash::set('error','Data pengaduan tidak ditemukan.');$this->redirect('pengaduan');}$error='';$statusList=['Menunggu','Proses','Selesai'];if($_SERVER['REQUEST_METHOD']==='POST'){if(!Csrf::verify($_POST['csrf_token']??null))$error='Sesi form sudah kedaluwarsa, silakan coba lagi.';else{$nis=trim($_POST['nis']??'');$idKategori=trim($_POST['id_kategori']??'');$status=trim($_POST['status']??'Menunggu');$data=['lokasi'=>trim($_POST['lokasi']??''),'ket'=>trim($_POST['ket']??''),'feedback'=>trim($_POST['feedback']??'')];if($nis===''||$idKategori===''||$data['lokasi']===''||$data['ket']===''||!in_array($status,$statusList,true))$error='Data belum lengkap atau status tidak valid.';elseif(!ctype_digit($nis)||!ctype_digit($idKategori))$error='NIS atau kategori tidak valid.';else{$data['nis']=(int)$nis;$data['id_kategori']=(int)$idKategori;$data['status']=$status;if($this->model->update($id,$data)){Flash::set('success','Pengaduan berhasil diperbarui.');$this->redirect('pengaduan');}$error='Gagal memperbarui pengaduan.';}$pengaduan=array_merge($pengaduan,['nis'=>$nis,'id_kategori'=>$idKategori],$data);}}$this->view('pengaduan/form',['mode'=>'edit','error'=>$error,'data'=>$pengaduan,'siswaList'=>$this->siswaModel->all(),'kategoriList'=>$this->kategoriModel->allAktif(),'statusList'=>$statusList]);}
    public function delete(): void {Auth::requireAdmin();$id=(int)($_GET['id']??0);if($id>0&&$this->model->delete($id))Flash::set('success','Pengaduan berhasil dihapus.');else Flash::set('error','Gagal menghapus pengaduan.');$this->redirect('pengaduan');}
}
