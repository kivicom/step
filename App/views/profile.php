<?php $this->layout('layout/layout', ['title' => 'Профиль','auth' => $auth,'user' => $user]) ?>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Профиль пользователя</h3></div>

                        <div class="card-body">
                            <?php echo flash()->display();?>

                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $this->e($user['id']);?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Name</label>
                                            <input type="text" class="form-control" name="username" id="exampleFormControlInput1" value="<?php echo $this->e($user['username']);?>">

                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Email</label>
                                            <input type="" class="form-control" name="email" id="exampleFormControlInput1" value="<?php echo $this->e($user['email']);?>">
                                            <?php if(isset($_SESSION['email_err'])):?>
                                                <span class="text text-danger">
                                                    <?php echo $_SESSION['email_err']; unset($_SESSION['email_err']);?>
                                                </span>
                                            <?php endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Аватар</label>
                                            <input type="file" class="form-control" name="image" id="exampleFormControlInput1">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <?php if(!empty($user['image'])):?>
                                            <img src="cabinet/user<?php echo $_SESSION['user']['id'];?>/<?php echo $user['image'];?>" alt="" class="img-fluid">
                                        <?php else:?>
                                            <img src="../../public/img/no-user.jpg" alt="" class="img-fluid">
                                        <?php endif;?>
                                    </div>
                                    <input type="hidden" name="edit_user">

                                    <div class="col-md-12">
                                        <button class="btn btn-warning">Edit profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Безопасность</h3></div>

                        <div class="card-body">
                            <?php if(isset($_SESSION['pass_success'])):?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_SESSION['pass_success']; unset($_SESSION['pass_success']);?>
                            </div>
                            <?php endif;?>

                            <?php if(isset($_SESSION['empty_err'])):?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $_SESSION['empty_err']; unset($_SESSION['empty_err']);?>
                                </div>
                            <?php endif;?>

                            <form action="" method="post">
                                <input type="hidden" name="upd">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Current password</label>
                                            <input type="password" name="current" class="form-control" id="exampleFormControlInput1">

                                            <?php if(isset($_SESSION['newpass_err'])):?>
                                                <span class="text text-danger">
                                                    <?php echo $_SESSION['newpass_err']; unset($_SESSION['newpass_err']);?>
                                                </span>
                                            <?php endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">New password</label>
                                            <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                                            <?php if(isset($_SESSION['pass_err'])):?>
                                                <span class="text text-danger">
                                                    <?php echo $_SESSION['pass_err']; unset($_SESSION['pass_err']);?>
                                                </span>
                                            <?php endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Password confirmation</label>
                                            <input type="password" name="password_confirmation" class="form-control" id="exampleFormControlInput1">
                                        </div>

                                        <button class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
