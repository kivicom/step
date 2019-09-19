<?php $this->layout('layout/layout', ['title' => 'Админ панель','auth' => $auth]) ?>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Админ панель</h3></div>

                        <div class="card-body">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Аватар</th>
                                    <th>Имя</th>
                                    <th>Дата</th>
                                    <th>Комментарий</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php if($items):?>
                                <?php foreach ($items as $item) :?>
                                <tr>
                                    <td>
                                        <?php if(empty($this->e($item['image']))):?>
                                            <img src="img/no-user.jpg" class="img-fluid" alt="..." width="64" height="64">
                                        <?php else:?>
                                            <img src="cabinet/user<?php echo $this->e($item['user_id']);?>/<?php echo $this->e($item['image']);?>" alt="" class="mr-3"  width="64" height="64">
                                        <?php endif;?>
                                    </td>
                                    <td><?php echo $this->e($item['name']);?></td>
                                    <td><?php echo date('d/m/Y', $this->e(strtotime($item['date'])));?></td>
                                    <td><?php echo $this->e($item['text']);?></td>
                                    <td>

                                        <?php if($this->e($item['published']) == 0):?>
                                            <form action="" method="POST">
                                                <button type="submit" class="btn btn-success" >Разрешить</button>
                                                <input type="hidden" name="id" value="<?php echo $this->e($item['cid']);?>">
                                                <input type="hidden" name="published" value="1">
                                            </form>
                                        <?php else:?>
                                            <form action="" method="POST">
                                                <button type="submit" class="btn btn-warning" >Запретить</button>
                                                <input type="hidden" name="id" value="<?php echo $item['cid'];?>">
                                                <input type="hidden" name="published" value="0">
                                            </form>
                                        <?php endif;?>
                                        <form action="" method="POST">
                                            <button type="submit" class="btn btn-danger" >Удалить</button>
                                            <input type="hidden" name="id" value="<?php echo $this->e($item['cid']);?>">
                                            <input type="hidden" name="remove" value="<?php echo $this->e($item['cid']);?>">
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                <?php endif;?>
                                </tbody>
                            </table>
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
            </div>
        </div>
    </main>
