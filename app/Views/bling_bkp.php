<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Subheader-->
						<div class="subheader py-3 py-lg-8 subheader-transparent" id="kt_subheader">
							<div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
								<!--begin::Info-->
								<div class="d-flex align-items-center mr-1">
									<!--begin::Page Heading-->
									<div class="d-flex align-items-baseline flex-wrap mr-5">
										<!--begin::Page Title-->
										<h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Pedido de compra
                                            <a type="button" class="" data-toggle="modal" data-target="#exampleModalCenter">
                                                <img  class="img_config_bling" src="/template/images/youtube.png" alt="" srcset=""> 
                                            </a>
                                        </h2>
										<!--end::Page Title-->
									</div>
									<!--end::Page Heading-->
								</div>
								<!--end::Info-->
							</div>
						</div>
						<!--end::Subheader-->
						<!--begin::Entry-->
						<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container">
                                <?php helper('mensagem');?>
                                <form action="<?php echo base_url();?>/Integracao/integracaoBling" method="POST" id="frm_pesquisa">
                                <?= csrf_field() ?>
                                    <div id="accordion">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                            <h5 class="mb-0" style="margin-bottom: 15px!important;" >
                                                <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-target="#collapseOne" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-search"></i>
                                                Pesquisa avançada</a>
                                            </h5>
                                            </div>

                                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <h5 style="margin-left: 12px;" >Data inicial</h5>
                                                            <div class="controls">
                                                                <input type="date"  id="data_inicial_tabela" name="data_inicial_tabela" value="<?php if(isset($_POST["data_inicial_tabela"])){ echo $_POST["data_inicial_tabela"];} ?>" class="form-control margin_filtro">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <h5 style="margin-left: 12px;" >Data final</h5>
                                                            <div class="controls">
                                                                <input type="date"  id="data_final_tabela" name="data_final_tabela" value="<?php if(isset($_POST["data_final_tabela"])){ echo $_POST["data_final_tabela"];} ?>" class="form-control margin_filtro">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h5 style="margin-left: 12px;" >Fornecedor</h5>
                                                            <div class="controls">
                                                                <select id="fornecedores" name="fornecedores" class="form-control">
                                                                    <option value="" >Selecione uma opção</option>
                                                                    <?php
                                                                        foreach($fornecedores as $fornecedor){                                                           
                                                                            $selected='';
                                                                            if(isset($_POST["fornecedores"])&&$_POST["fornecedores"]==$fornecedor->ID_FORNECEDOR){$selected='selected';}  
                                                                            echo '<option '.$selected.' value="'.$fornecedor->ID_FORNECEDOR.'" >'.$fornecedor->NOME.'</option>';
                                                                        }

                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <h5 style="margin-left: 12px;" >Estoque de segurança</h5>
                                                            <div class="controls">
                                                                <input type="text"  id="estoque_seguranca" name="estoque_seguranca" value="<?php if(isset($_POST["estoque_seguranca"])){ echo $_POST["estoque_seguranca"];} ?>" class="form-control margin_filtro">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="pesquisar" value="ok">
                                                    <div class="botao_filtro_tabela" >
                                                        <button type="button" style="color: #FFFFFF;" id="criar_pedido" class="btn btn-radius btn-success btn-lg">Criar pedido de compra</button>
                                                        <button type="button" style="color: #FFFFFF;" id="sincronizar_pedidos_recentes" class="btn btn-radius btn-warning btn-lg">Importar Pedidos</button>
                                                        <!-- <button type="button" style="color: #FFFFFF;" id="agrupar_pedido_compra" class="btn btn-radius btn-info btn-lg">Agrupar produtos</button> -->
                                                        <button type="button" id="envia_form" style="color: #FFFFFF;" class="btn btn-radius btn-primary btn-lg">Pesquisar</button>
                                                    </div><br><br><br>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </form>
								<!--begin::Card-->
								<div class="card card-custom gutter-b">
									<div class="card-header flex-wrap border-0 pt-6 pb-0">

									</div>
									<div class="card-body">
                                        <table id="dados_bling_pedido" class="display nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;" >DESCRIÇÃO</th>
                                                <th style="text-align: center;" >SKU</th>
                                                <th style="text-align: center;" >CÓD <br>FORNCEDOR</th>
                                                <th style="text-align: center;" >QTD VENDAS<br> (90 DIAS)</th>
                                                <th style="text-align: center;" >QTD VENDAS<br> (60 DIAS)</th>
                                                <th style="text-align: center;" >QTD VENDAS<br> (30 DIAS)</th>
                                                <th style="text-align: center;" >QTD <br>VENDAS</th>
                                                <th style="text-align: center;" >TAXA MÉDIAS <br>VENDAS P/ DIA </th>
                                                <th style="text-align: center;" >DATA ULTIMA <br>VENDA </th>
                                               
                                                <?php 
                                                    foreach ($depositos as $key => $deposito) {
                                                        echo '<th style="text-align: center;   text-transform: uppercase;" >'.$deposito->NOME.'</th>';
                                                    }
                                                ?>
                                                <th style="text-align: center;" >TOTAL <br> ESTOQUE</th>
                                                <th style="text-align: center;" >DIAS DURAÇÃO <br> ESTOQUE </th>
                                                <th style="text-align: center;" >QTD <br> PREVISTA</th>
                                                <th style="text-align: center;" >ESTOQUE <br> SEGURANÇA</th>
                                                <th style="text-align: center;" >QTD <br> PEDIDO</th>
                                                <th style="text-align: center;" >OBSERVAÇÕES</th>
                                                <th style="text-align: center;" >PREÇO <br> CUSTO</th>
                                                <th style="text-align: center;" >DESC <br> FORNCEDOR</th>
                                                <th style="text-align: center;" >CLASSIFICAÇÃO</th>
                                                <th style="text-align: center; display: none;" >ID PRODUTO</th>
                                                <th style="text-align: center; display: none;" >DESCRICAO COMPLETA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $total_qtd_vendas = 0;
                                                $total_qtd_vendas_90_dias = 0;
                                                $total_qtd_vendas_60_dias = 0;
                                                $total_qtd_vendas_30_dias = 0;
                                                $total_preco_custo = 0;
                                                if($resultados != ""){
                                                    //var_dump($resultados);die();
                                                    foreach ($resultados as $resultado) {
                                                        echo '<tr class="linhas" >'; 
                                                            echo '	<td style="text-align: center;"  class="descricao_produto" title="'.$resultado['DESCRICAO_PRODUTO'].'" >'.substr($resultado['DESCRICAO_PRODUTO'],0, 20).'...</td>';
                                                            if(count($resultado['CODIGOS_AGRUPADOS']) > 1){
                                                                $title = "Os seguintos SKU's foram agrupados por código do fornecedor ".$resultado['CODIGO_FORNECEDOR'];
                                                                foreach ($resultado['CODIGOS_AGRUPADOS'] as $codigos_agrupados) {
                                                                    $title .= "\n";
                                                                    $title .= $codigos_agrupados;
                                                                }
                                                                $icone_atencao = '<i data="'.$resultado['CODIGO_FORNECEDOR'].'" style="margin-left: 5px; margin-top: 12px; color: #f6c736" title="'.$title.'" class=" fas fa-exclamation-triangle"></i>';
                                                            }else{
                                                                $icone_atencao = '';
                                                            }
                                                            echo '	<td style="text-align: center;"  class="sku_produto" >'.mb_strtoupper($resultado['SKU'],'UTF-8').'<span id="span_'.$resultado['SKU'].'" style="display: none;"></span>'.$icone_atencao.'</td>';
                                                            echo '	<td style="text-align: center;" >'.mb_strtoupper($resultado['CODIGO_FORNECEDOR'],'UTF-8').'</td>';
                                                            echo '	<td style="text-align: center;" >'.mb_strtoupper($resultado['QTD_VENDAS_90_DIAS'],'UTF-8').'</td>';
                                                            $total_qtd_vendas_90_dias += $resultado['QTD_VENDAS_90_DIAS'];
                                                            echo '	<td style="text-align: center;" >'.mb_strtoupper($resultado['QTD_VENDAS_60_DIAS'],'UTF-8').'</td>';
                                                            $total_qtd_vendas_60_dias += $resultado['QTD_VENDAS_60_DIAS'];
                                                            echo '	<td style="text-align: center;" >'.mb_strtoupper($resultado['QTD_VENDAS_30_DIAS'],'UTF-8').'</td>';
                                                            $total_qtd_vendas_30_dias += $resultado['QTD_VENDAS_30_DIAS'];
                                                            echo '	<td style="text-align: center;" ><span class="badge badge-info">'.mb_strtoupper($resultado['QTD_VENDAS'],'UTF-8').'</span></td>';
                                                            $total_qtd_vendas += $resultado['QTD_VENDAS'];
                                                            echo '	<td style="text-align: center;" >'.mb_strtoupper($resultado['TAXA_MEDIA_VENDAS_POR_DIA'],'UTF-8').'</td>';
                                                            echo '	<td style="text-align: center;" >'.inverterData($resultado['DATA_ULTIMA_VENDA']).'</td>';
                                                            foreach ($depositos as $key => $deposito) {
                                                                if(isset($resultado['ESTOQUE-'.$deposito->ID_DEPOSITO]) && $resultado['ESTOQUE-'.$deposito->ID_DEPOSITO] != null){
                                                                    echo '	<td style="text-align: center;" >'.$resultado['ESTOQUE-'.$deposito->ID_DEPOSITO].'</td>';
                                                                }else{
                                                                    echo '	<td style="text-align: center;" >0</td>';
                                                                }
                                                            }
                                                            if($resultado['ESTOQUE_TOTAL'] != 0){
                                                                echo '	<td style="text-align: center;">'.mb_strtoupper($resultado['ESTOQUE_TOTAL'],'UTF-8').'</td>';
                                                            }else{
                                                                echo '	<td style="text-align: center;"><span class="badge badge-danger">'.$resultado['ESTOQUE_TOTAL'].'</span></td>';
                                                            }
                                                            echo '	<td style="text-align: center;" >'.mb_strtoupper($resultado['DIAS_DURACAO_ESTOQUE'],'UTF-8').'</td>';
                                                            echo '	<td style="text-align: center;"><span class="badge badge-success">'.$resultado['QTD_PREVISTA_ENTRADA'].'</span></td>';
                                                            echo '	<td style="text-align: center;">'.mb_strtoupper($resultado['ESTOQUE_SEGURANCA'],'UTF-8').'</td>';
                                                            echo '	<td id="td_'.$resultado['SKU'].'" style="text-align: center" > <input type="number" value="'.$resultado['ESTOQUE_SEGURANCA'].'" class="form-control input_qtd_pedido" id="'.$resultado['SKU'].'"  name=""><span id="span_'.$resultado['SKU'].'" style="display: none;"></span> </td>';
                                                            echo '	<td style="text-align: center;">'.$resultado['OBSERVACOES'].'</td>';
                                                            if($resultado['ESTOQUE_SEGURANCA'] != "" && $resultado['ESTOQUE_SEGURANCA'] != NULL){
                                                                $valor_calculado = floatval($resultado['PRECO_CUSTO']) * floatval($resultado['ESTOQUE_SEGURANCA']);
                                                            }else{
                                                                $valor_calculado = floatval($resultado['PRECO_CUSTO']);
                                                            }
                                                            $total_preco_custo += $valor_calculado;
                                                            echo '	<td style="text-align: center;" > <input readonly value="R$ '.$resultado['PRECO_CUSTO'].'" type="hidden" name="" class="input_preco_original" id="input_preco_original_'.$resultado['SKU'].'"><span class="span_preco"  id="span_preco_'.$resultado['SKU'].'" >R$ '.$valor_calculado.'</span></td>';
                                                            echo '	<td style="text-align: center;"  class="descricao_produto" title="'.$resultado['DESCRICAO_FORNECEDOR'].'" >'.substr($resultado['DESCRICAO_FORNECEDOR'],0, 20).'...</td>';
                                                            echo '	<td style="text-align: center;" >'.$resultado['CLASSIFICACAO'].'</td>';
                                                            echo '	<td style="text-align: center; display: none;" class="descricao_completa_produto" >'.$resultado['DESCRICAO_PRODUTO'].'</td>';
                                                            echo '	<td style="text-align: center; display: none;" class="id_produto_bling" >'.$resultado['ID_PRODUTO_BLING'].'</td>';
                                                        echo '</tr>';
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th><?php echo $total_qtd_vendas_90_dias;?></th>   
                                                <th><?php echo $total_qtd_vendas_60_dias;?></th>   
                                                <th><?php echo $total_qtd_vendas_30_dias;?></th>   
                                                <th><?php echo $total_qtd_vendas;?></th>   
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <?php 
                                                    foreach ($depositos as $key => $deposito) {
                                                        echo '<th></th>';
                                                    }
                                                ?>
                                                <th></th>
                                                <th></th>       
                                                <th></th>                                        
                                                <th></th>      
                                                <th></th>                                    
                                                <th><span id="total_preco_custo" >R$ 0,00</span> </th>                                                        
                                                <th></th>    
                                                <th></th>      
                                                <th style="display: none;" ></th>
                                                <th style="display: none;" ></th>
                                            </tr>
                                        </tfoot>
                                    </table>
									</div>
								</div>
								<!--end::Card-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Entry-->
					</div>
					<!--end::Content-->
                    <script src="/template/assets/plugins/global/plugins.bundle.js"></script>
                    <script src="/template/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
                    <script src="/template/assets/js/scripts.bundle.js"></script>
                    <!--begin::Page Scripts(used by this page)-->
                    <script src="/template/assets/js/pages/widgets.js"></script>
                    <!--begin::Page Vendors(used by this page)-->
                    <script src="/template/assets/plugins/custom/datatables/datatables.bundle.js"></script>
                    <script src="/template/assets/js/pages/crud/datatables/extensions/buttons.js"></script>
                    <!--end::Page Vendors-->
                    <!--begin::Page Scripts(used by this page)-->
                    <script src="/template/assets/js/pages/crud/datatables/basic/scrollable.js"></script>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Entenda como funciona</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            <iframe width="750" height="428" src="https://www.youtube.com/embed/2yc4wP4R41o" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                            </div>
                        </div>
                    </div>