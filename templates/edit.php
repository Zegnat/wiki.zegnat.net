<?php $this->layout('shared', $this->data) ?>

<?php $this->start('main') ?>
<h1>Editor</h1>
<form action="<?= $path ?>" method="post">
    <label for="editor">Content</label>
    <textarea name="content" id="editor"><?= $body ?></textarea>
    <label for="passcode">Passcode</label>
    <input type="text" name="passcode" id="passcode">
    <input type="submit" value="Save">
</form>
<?php $this->stop() ?>

<?php $this->start('footer') ?>
<a rev="edit" href="<?= $path ?>">View <i><?= $title ?></i></a>
<?php $this->stop() ?>
