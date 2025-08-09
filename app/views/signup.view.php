<?php require '../app/views/partials/header.php'; ?>
<div>
    
    <form method="post">
        
        <?php if(!empty($errors)):?>
            <div class="alert alert-danger">       
                <?= implode("<br>",$errors)?> 
            </div>
            <?php endif;?>
        
            <!-- <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> -->
            <h1>Create account</h1>
            
            <div class="form-group">
                <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
            </div>

            <div class="form-group">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>

            <div>
                <label>
                    <input name="terms" type="checkbox" value="1" >Accept terms
                </label>
            </div>
            
            <button class="btn btn-primary" type="submit">Create</button> 
            <a href="<?=ROOT?>/home">Home</a>
            <a href="<?=ROOT?>/login">Login</a>
            
        </form> 
    
</div>     

<?php require '../app/views/partials/footer.php'; ?>