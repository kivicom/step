<?php $this->layout('layout/layout', ['title' => 'Авторизация','auth' => $auth]) ?>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">
                            <?php echo flash()->display();?>
                            <form method="POST" action="">

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email"  autocomplete="email" >
                                        <?php if(isset($_SESSION['email_err'])):?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $_SESSION['email_err']; unset($_SESSION['email_err']);?>
                                            </div>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password"  autocomplete="current-password">
                                    </div>
                                    <?php if(isset($_SESSION['pass_err'])):?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $_SESSION['pass_err']; unset($_SESSION['pass_err']);?>
                                        </div>
                                    <?php endif;?>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                                            <label class="form-check-label" for="remember">
                                                Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
