<?php $this->layout('layout/layout', ['title' => 'Главная','auth' => $auth]) ?>

    <main class="py-4">
        <div class="container">
            <nav class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Комментарии</h3></div>

                        <div class="card-body">
                            <?php  flash()->display();?>

                            <!--12/10/2025-->
                            <?php if($items) :?>
                                <?php foreach ($items as $item) :?>
                                    <?php if($this->e($item['published'])):?>
                                        <div class="media">
                                            <?php if(empty($this->e($item['image']))):?>
                                                <img src="img/no-user.jpg" class="mr-3" alt="..." width="64" height="64">
                                            <?php else:?>
                                                <img src="cabinet/user<?php echo $this->e($item['user_id']);?>/<?php echo $this->e($item['image']);?>" alt="" class="mr-3"  width="64" height="64">
                                            <?php endif;?>
                                            <div class="media-body">
                                                <h5 class="mt-0"><?php echo $this->e($item['username']);?></h5> <!--Выводим имя коментатора-->
                                                <span>
                                                    <small>
                                                        <?php echo date('d/m/Y', $this->e(strtotime($item['date'])));?>
                                                    </small>
                                                </span> <!--Выводим дату когда оставили комент-->
                                                <p>
                                                    <?php echo $this->e($item['text']);?><!--Выводим сам текст комента-->
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif;?>
                                <?php endforeach;?>
                            <?php endif;?>
                        </div>
                    </div>
                    <div id="pagination_area">
                        <ul class="pagination justify-content-end">
                            <?php if ($paginator->getPrevUrl()): ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $paginator->getPrevUrl(); ?>">&laquo; Previous</a></li>
                            <?php endif; ?>

                            <?php foreach ($paginator->getPages() as $page): ?>
                                <?php if ($page['url']): ?>
                                    <li <?php echo $page['isCurrent'] ? 'class="active page-item"' : 'page-item'; ?>>
                                        <a class="page-link" href="<?php echo $page['url']; ?>"><?php echo $page['num']; ?></a>
                                    </li>
                                <?php else: ?>
                                    <li class="disabled page-item"><span><?php echo $page['num']; ?></span></li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if ($paginator->getNextUrl()): ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $paginator->getNextUrl(); ?>">Next &raquo;</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>


                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Оставить комментарий</h3></div>

                        <div class="card-body">
                            <?php if(isset($_SESSION['user'])):?>
                            <form action="" method="POST">
                                <div class="form-group">
                                    <?php if(!isset($_SESSION['user'])):?>
                                        <label for="exampleFormControlTextarea1">Имя</label>
                                        <input name="name" class="form-control" id="exampleFormControlTextarea1" />
                                    <?php else:?>
                                        <input name="name" type="hidden" class="form-control" id="exampleFormControlTextarea1" value="<?php echo $_SESSION['user']['name'];?>"/>
                                        <input name="user_id" type="hidden" class="form-control" id="exampleFormControlTextarea1" value="<?php echo $_SESSION['user']['id'];?>"/>
                                    <?php endif;?>
                                    <?php if(isset($_SESSION['name_err'])):?>
                                        <span class="text-danger">Ошибка. Поле имя не заполненно</span>
                                    <?php unset($_SESSION['name_err']);?>
                                    <?php endif;?>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Сообщение</label>
                                    <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                    <?php if(isset($_SESSION['text_err'])):?>
                                        <span class="text-danger">Ошибка. Поле комментарий не заполненно</span>
                                        <?php unset($_SESSION['text_err']);?>
                                    <?php endif;?>
                                </div>
                                <button type="submit" class="btn btn-success">Отправить</button>
                            </form>
                            <?php else:?>
                                <span>Чтобы оcтавить комментарий, необходимо <a href="../login">авторизоваться</a></span>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
