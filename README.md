# query-builder
Query Builder Mysql With PDO

Cara penggunaan

## Database Connection
Edit di file ```qb.class.php``` di bagian paling atas, isi dengan data login mysql anda.
```php
protected	$host 		= 'localhost',
			$dbname 	= 'itclub',
			$username 	= 'root',
			$password 	= 'wowkerenganhahahaha';
```

## Quick Start
```php
<?php 
require_once "src/qb.class.php";

$init = QueryBuilder::startConnection();
$results = $init->setTable('users')->select('name')->all();

foreach($results as $row):
  echo $row-name;
endforeach;

```

## Select Data
Memilih semua data pada tabel
```php
$init->select('id,name,username')->all();
```
Hilangkan isian parameter pada method select jika ingin memilih semua kolom dalam tabel.
Memilih satu data dari dalam tabel
```php
$init->select('id,name,username')->first();
```

## Where
Dalam query builder ini, anda bisa menggunakan multiple where, ```OR``` atau ```AND```
Contoh:
```php
$init->select('id,name,username')->where('id','=',1,'OR')->where('id','=',70)->all();
```

Parameter ke empat pada method where bisa dihilangkan jika hanya menggunakan satu method where saja. Kata ```OR``` juga bisa diganti menjadi ```AND```.

## Limit
Membatasi jumlah records yang mau di ambil pada suatu tabel.
```php
$init->select('id,name,username')->limit(50)->all();
```

## OrderBy
Mengurutkan data pada saat pengambilan records dalam tabel sangat penting, berikut caranya jika menggunakan query builder ini
```php
$init->select('id,name,username')->orderBy('id','desc')->limit(100)->all();
```
Untuk tipe pengurutan, bisa menggunakan ```ASC``` ataupun ```DESC```, disesuaikan dengan kubutuhan.

## Insert
Menambahkan record ke dalam tabel
```php
$init->insert([
  'name'=>'Satria Aji Putra',
  'address'=>'Sukabumi, Jawa Barat',
  'username'=>'satmaxt',
]);
```
## Update
Untuk update, kurang lebih sama seperti insert, namun di akhir ditambahkan method where dan save. Seperti berikut
```php
$init->update([
  'name'=>'Satria Aji Putra',
  'address'=>'Sukabumi, Jawa Barat',
  'username'=>'satmaxt',
])->where('id','=',1)->save();
```

## Delete
untuk delete, anda hanya tinggal menggunakan method where lalu diakhiri dengan method delete
```php
$init->where('id','=',1)->delete();
```
