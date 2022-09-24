<?php include('../template/cabecera.php'); ?>
<?php
$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtAutor=(isset($_POST['txtAutor']))?$_POST['txtAutor']:"";
$txtLink=(isset($_POST['txtLink']))?$_POST['txtLink']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";



/*echo $txtID. "<br/>";
echo $txtNombre. "<br/>";
echo $txtImagen. "<br/>";
echo $accion. "<br/>";*/

include('../config/bd.php');


switch($accion){
case "Agregar":
    $sentenciaSQL= $conexion->prepare("INSERT INTO libros (Nombre, Imagen, Autor, link) VALUES (:Nombre, :Imagen, :Autor, :Link);");
    $sentenciaSQL->bindParam(':Nombre', $txtNombre);
    $sentenciaSQL->bindParam(':Autor', $txtAutor);
    $sentenciaSQL->bindParam(':Link', $txtLink);    
    
    $fecha= new datetime();
    $nombreArchivo =($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]: "imagen.jpg";
    $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

    if($tmpImagen!=""){
        move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
    }

    $sentenciaSQL->bindParam(':Imagen', $nombreArchivo);
    
    $sentenciaSQL->execute();
    header("Location:productos.php");
    break;

case "Modificar":
    $sentenciaSQL= $conexion->prepare("UPDATE libros SET Nombre=:Nombre, Autor=:Autor, Link=:Link where id=:id ");
  
    $sentenciaSQL->bindParam(':Nombre', $txtNombre);
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->bindParam(':Autor', $txtAutor);
   $sentenciaSQL->bindParam(':Link', $txtLink);
   
    $sentenciaSQL->execute();


    if($txtImagen!=""){
        $fecha= new datetime();
        $nombreArchivo =($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]: "imagen.jpg";
        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];
        move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);

        $sentenciaSQL= $conexion->prepare("SELECT Imagen from libros where id=:id");
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->execute();
    $libro=$sentenciaSQL->fetch(PDO:: FETCH_LAZY);

    if(isset ($libro["Imagen"]) &&($libro["Imagen"]!="imagen.jpg ")	){
        if(file_exists("../../img/".$libro["Imagen"])){
            unlink("../../img/".$libro["Imagen"]);
        }

    }


    $sentenciaSQL= $conexion->prepare("UPDATE libros SET Imagen=:Imagen where id=:id");
    $sentenciaSQL->bindParam(':Imagen', $nombreArchivo);
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->execute();
    }
    //echo"Presionado boton Modificar";
    header("Location:productos.php");
    break;

case "Cancelar":
    header("Location:productos.php");
    break;
case "Seleccionar":
      // echo"Presionado boton Seleccionar";
      $sentenciaSQL= $conexion->prepare("SELECT * from libros where id=:id");
      $sentenciaSQL->bindParam(':id', $txtID);
      $sentenciaSQL->execute();
      $libro=$sentenciaSQL->fetch(PDO:: FETCH_LAZY);

      $txtNombre=$libro['Nombre'];
      $txtAutor=$libro['Autor'];
      $txtLink=$libro['Link'];
      $txtImagen=$libro['Imagen'];
     
      
        break;
case "Borrar":
    $sentenciaSQL= $conexion->prepare("SELECT Imagen from libros where id=:id");
    $sentenciaSQL->bindParam(':id', $txtID);
    $sentenciaSQL->execute();
    $libro=$sentenciaSQL->fetch(PDO:: FETCH_LAZY);


    if(isset ($libro["Imagen"]) &&($libro["Imagen"]!="imagen.jpg ")	){
        if(file_exists("../../img/".$libro["Imagen"])){
            unlink("../../img/".$libro["Imagen"]);
        }

    }


      //echo"Presionado boton Borrar";
      $sentenciaSQL= $conexion->prepare("DELETE from libros where id=:id");
      $sentenciaSQL->bindParam(':id', $txtID);
      $sentenciaSQL->execute();
      header("Location:productos.php");
      
            break;

}

$sentenciaSQL= $conexion->prepare("SELECT * from libros");
$sentenciaSQL->execute();
$listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de Libros 
        </div>

        <div class="card-body">
        <form method ="POST" enctype="multipart/form-data">
        <div class = "form-group">
        <label for="txtID">ID: </label>
        <input type="text" required readonly class="form-control"  value= "<?php echo $txtID ?>" name="txtID"  id="txtID" placeholder="ID">
        </div>

        <div class = "form-group">
        <label for="txtNombre">Nombre: </label>
        <input type="text" required class="form-control" value= "<?php echo $txtNombre ?>" name="txtNombre" id="txtNombre" placeholder="Nombre">
        </div>

        <div class = "form-group">
        <label for="txtAutor">Autor: </label>
        <input type="text" required class="form-control" value= "<?php echo $txtAutor ?>" name="txtAutor" id="txtAutor" placeholder="Autor">
        </div>

       
        <div class = "form-group">
        <label for="txtLink">Link para libro: </label>
        <input type="text" required class="form-control" value= "<?php echo $txtLink ?>" name="txtLink" id="txtLink" placeholder="Link">
        </div>   
   
        <div class = "form-group">
        <label for="txtImagen">Imagen: </label>
     <br/>
       <?php
       if($txtImagen!=""){?>
        <img src="../../img/<?php echo $txtImagen;?>" width=50 alt="">  
       <?php } ?>
        <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Imagen">
        </div>

        <div class="btn-group" role="group" aria-label="">
        <button type="submit" name="accion" <?php echo ($accion="Seleccionar")? "disable":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")? "disable":""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
        <button type="submit" name="accion" <?php echo  ($accion!="Seleccionar")? "disable":""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
        </div>

        
    
        </form>
 
           
        </div>
       
    </div>
 
   
</div>

<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Autor</th>
                <th>Link</th>
                <th>Imagenes</th>
                <th>Acciones</th>
              
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaLibros as $libro) {          ?>
            <tr> 
                <td><?php echo $libro ['Id'];?></td>
                <td><?php echo $libro ['Nombre'];?></td>
                <td><?php echo $libro ['Autor'];?></td>
                <td><?php echo $libro ['Link'];?></td>
             
                
                <td><img src="../../img/<?php echo $libro ['Imagen'];?>" width=50 alt=""></td>
                <td>

                <form method="post">
                    <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro ['Id'];?>"/>
                    <input type ="submit" name="accion" value= "Seleccionar" class="btn btn-primary"/>
                    <input type ="submit" name="accion" value= "Borrar" class="btn btn-danger"/>

                </form>
                
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include('../template/piedepagina.php'); ?>