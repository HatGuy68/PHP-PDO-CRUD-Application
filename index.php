<?php
require_once('templates/header.php');
require_once('templates/footer.php');
?>

<div class="container">
    <div class="alert alert alert-primary" role="alert">
        <h4 class="text-primary text-center">PHP CRUD Application Using jQuery Ajax</h4>
    </div>
    <div class="alert alert-success text-center message" role="alert">

    </div>

<?php
require_once('templates/form.php');
?>

    <div class="row mb-3">
        <div class="col-9">
            
        </div>
        <div class="col-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#userModal" id="addnewbtn">Add New <i class="fa fa-user-circle-o"></i></button>
        </div>
    </div>

<?php require_once('templates/players_table.php'); ?>

    <nav id="pagination">
    </nav>
    <input type="hidden" name="currentpage" id="currentpage" value="1">
</div>
<div>

<?php require_once('templates/footer.php'); ?>