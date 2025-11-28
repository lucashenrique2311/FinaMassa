<?php
	if(isset($_GET['msg']))
	{
		if(!empty($_GET['msg']))
		{
			if($_GET['tipo_msg'] == "sucesso")
			{
?>

				<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-check"></i> Sucesso!</h4> 
					<?php echo $_GET['msg'];?>
				</div>

<?php
			}
			else if($_GET['tipo_msg'] == "erro")
			{
?>

				<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-exclamation-triangle"></i> Erro!</h4> 
					<?php echo $_GET['msg'];?>
				</div>

<?php
			}
			else if($_GET['tipo_msg'] == "invalido")
			{
?>

				<div class="alert alert-warning alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-exclamation-triangle"></i> atenção!</h4> 
					<?php echo $_GET['msg'];?>
				</div>

<?php
			}
		}
	}
?>