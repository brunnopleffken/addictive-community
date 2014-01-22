<div class="box">
	<form action="hahaha.php" method="post" class="validate">
		<div class="inputBox">
			<div class="label">Username</div>
			<div class="field"><input type="text" class="small required"></div>
		</div>
		<div class="inputBox">
			<div class="label">Password</div>
			<div class="field"><input type="password" class="small required"></div>
		</div>
		<div class="inputBox">
			<div class="label">E-mail Address</div>
			<div class="field"><input type="text" class="medium required email"></div>
		</div>
		<div class="inputBox">
			<div class="label">URL</div>
			<div class="field"><input type="text" class="medium required url" value="http://"></div>
		</div>
		<div class="inputBox">
			<div class="label">Message</div>
			<div class="field"><textarea rows="5" cols="60"></textarea></div>
		</div>
		<div class="inputBox">
			<div class="label">Sector</div>
			<div class="field"><select class="small required">
				<option value="">---</option>
				<option value="PR">Paraná</option>
				<option value="RJ">Rio de Janeiro</option>
				<option value="SP">São Paulo</option>
			</select></div>
		</div>
		<div class="inputBox">
			<div class="label"></div>
			<div class="field"><input type="submit" value="Enviar"> <p class="errorMessage">An error occoured! Please, check the highlighted fields.</p></div>
		</div>
	</form>
</div>