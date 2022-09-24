<?php include("template/cabecera.php");?>

<?php  
include("administrador/config/bd.php");
$sentenciaSQL= $conexion->prepare("SELECT * from libros");
$sentenciaSQL->execute();
$listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach($listaLibros as $libro){?>
<div class="col-md-3">
<div class="card">
    <img class="card-img-top" src= "./img/<?php echo $libro['Imagen'];?>">
    <div class="card-body">
        <h4 class="card-title"><?php echo $libro['Nombre'] ?> </h4>
        <h5 class="card-title"><?php echo $libro['Autor'] ?> </h5>
        
        
        <a name="" id="" class="btn btn-primary" href="<?php echo $libro['Link'] ?>" role="button">Mas info...</a>
    </div>
</div>
</div>
<?php }?>







<?php include("template/piedepagina.php");?>