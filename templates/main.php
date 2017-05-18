<?php $this->layout('shared', $this->data) ?>

<?php $this->start('main') ?>
<?= $body ?>
<?php $this->stop() ?>

<?php $this->start('footer') ?>
<a rel="edit" href="<?= $path ?>?edit">Edit <i><?= $title ?></i></a>
<?php $this->stop() ?>
