<div id="body">
    <ul><?=validation_errors('<li style="color: red;">', '</li>');?></ul>

	<br>

	<form method="post" action="<?=current_url()?>" enctype="multipart/form-data">
		¿Aceptas los términos? <input type="checkbox" name="terms" value="<?=set_value('terms')?>"><br>

		first_name <input type="text" name="first_name" value="<?=set_value('first_name')?>"><br>
		last_name <input type="text" name="last_name" value="<?=set_value('last_name')?>"><br>
		password <input type="text" name="password" value="<?=set_value('password')?>"><br>
		password confirm <input type="text" name="password_confirm" value="<?=set_value('password_confirm')?>"><br>

		Field <input type="text" name="field" value="<?=set_value('field')?>"><br>

		Archivo
		<input type="file" name="image" value="<?=set_value('image', '')?>"><br>

		<button type="submit">Enviar</button>
	</form>
</div>
