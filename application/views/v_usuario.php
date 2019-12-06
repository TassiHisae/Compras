<html>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <form id="formCadastro">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <h4>Cadastro de usuários</h4>
                        </div>

                        <div class="panel-body">
                            <div class="form-group col-lg-6">
                                <label for="textNome" class="control-label">Usuário:</label>
                                <input name="usuario" id="usuario" class="form-control" placeholder="Digite seu Nome" onblur = "verifica();" type="text" required>
                            </div> 
                            <div class="form-group col-lg-6">
                                <label for="textUsario" class="control-label">Tipo:</label>
                                <select name = "cmb-tipo" id = "cmb-tipo" 
                                    class = "form-control selectpicker" data-container = "body" data-width = "100%" required>
                                    <option value = "1">Selecione o Tipo</option>
                                    <option>Administrador</option>
                                    <option>Comum</option>
                                </select>
                            </div>                       
                            <div class="form-group col-lg-6">      
                                <label for="inputPassword" class="control-label">Senha</label>      
                                <input type="password" class="form-control" placeholder="Informe sua senha" 
                                      name="senha" id="senha" data-minlength="6" required>      
                            </div>   
                            <div class="form-group col-lg-6">      
                                <label for="inputPassword" class="control-label">Confirme a Senha</label>      
                                <input type="password" class="form-control" placeholder="Informe sua senha" 
                                      name="csenha" id="csenha" data-minlength="6" required>      
                            </div>    
                        </div>
                        <div class="panel-footer clearfix">
                            <div class="btn-group pull-left">      
                                <button type="reset" class="btn btn-lg btn-danger" id = "btnlimpar">Limpar</button>
                            </div> 
                            <div class="btn-group pull-right">      
                                <button type="submit" class="btn btn-lg btn-primary">Salvar</button>
                            </div> 
                        </div>
                    </div>
                </form>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h1 class="panel-title">Usuários cadastrados</h1>
                    </div>
                    <div class="panel-body margem">
                        <table id ="tableusu"
                            data-toggle ="table"
                            data-height ="205"
                            data-search ="true"
                            accesskey=""
                            data-side-pagination ="client"
                            data-pagination ="true"
                            data-page-list="[5,10,15]"
                            data-pagination-first-text="First"
                            data-pagination-pre-text="Previous"   
                            data-pagination-next-text="Next"
                            data-pagination-last-text="Last"
                            data-url= 'Usuario/listar'>  
                            <!--Endereço do Controller responsável em buscar os dados da lista -->
                            <thead>
                                <tr>
                                    <th data-field = 'user' class = "col-md-3 text-left">Usuario</th> 
                                    <!--campo que retornará do Contoller deverá ser incluídio no data-field -->
                                    <th data-field = 'senha' class = "col-md-3">Senha</th>                    
                                    <!--campo que retornará do Contoller deverá ser incluídio no data-field -->
                                    <th data-field = 'tipo' class = "col-md-2 text-left">Tipo</th>   
                                    <th data-field = 'statuss' class = "col-md-2 text-left">Status</th>   
                                    <th  class = "col-md-2" data-formatter="opcoes"  data-field = "user" >Ação</th>
                                    <!--colocaremos a função data-formatter que chamará a função JavaScript opcoes
                                        e não podemos esquecer de amarrar no data-field o campo que será o parâmetro 
                                        de busca -->
                                </tr>

                            </thead>                        
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //document.getElementById('mcmb-tipo').value = "3";
        $(document).ready(function(){
            $("#formCadastro").submit(function(){
                if(verificaSenha($('#senha').val(),$('#csenha').val()) == true || verificaTipo($('#cmb-tipo').val()) == true){
        
                }else{
                    $.ajax({ //abrindo o ajax onde estamos pegando o método os dados através de post, onde enviamos para a controller usuario na função cadastrar
                        type: "POST",
                        url: 'usuario/cadastrar',
                        data: $("#formCadastro").serialize(),
                        success: function(data){
                            if ($.trim(data) == 1 ) 
                            {
                                $('#formCadastro').trigger("reset");
                                swal({title:"OK!", text: "Dados salvos com sucesso", type:"success"});
                            }else{
                                swal({title:"Atenção!", text: "Erro ao inserir, verifique os dados", type:"error"});
                            }
                        },
                        beforeSend: function(){
                            swal({title:"Aguarde!", text: "Carregando...", imageUrl: "assets/img/gifs/preloader.gif", showConfirmButton: false});
                        },
                        error: function(){
                            alert('Erro inesperado.');
                        }
                    });
                    return false;
                }
            });
            //refresh na tabela, a cada 5 segundos
            setInterval(function(){
                var $table = $('#tableusu');
                $table.bootstrapTable('refresh');
            }, 5000);
        });
        //opcoes está demonstrando os dois botões um de lixeira e um para modificação de um usuario
        function opcoes(value, row, index){
            if(row.statuss == 'DESATIVADO'){
                var opcoes = '<button class="btn btn-xs btn-warning text-center" type="button" onclick="reativa_usuario('+"'"+value+"'"+');"><span class="glyphicon glyphicon-open"></span></button>';
            }else{
                var opcoes = '<button class="btn btn-xs btn-primary text-center" type="button" onclick="busca_usuario('+"'"+value+"'"+');"><span class="glyphicon glyphicon-pencil"></span></button> \n\
                              <button class="btn btn-xs btn-danger text-center" type="button" onclick="desativa_usuario('+"'"+value+"'"+');"><span class="glyphicon glyphicon-trash"></span></button>';
            }
            return opcoes;
        };
        // busca de usuario que realizará a alteração do usuario através da modal que abrirá para ele 
        function busca_usuario(user){
                //abrindo uma modal
                $('#alteracao').modal('show');
                $.ajax({
                    type: "post",
                    url: 'usuario/consalterar',
                    dataType: 'json',
                    data: {'usuario':user},
                    success: function (data) {
                        $('#musuario').val(data[0].user);
                        $('#senha').val(data[0].senha);
                        swal.close();
                    },
                    beforeSend: function (){
                        swal({
                            title: "Aguarde!",
                            text: "Carregando...",
                            imageUrl: "assets/img/gifs/preloader.gif",
                            showConfirmButton: false
                        });
                    },
                    error: function(){ 
                        alert('Erro')
                    ;}
                 })
            };
        //função para alterar senhas de users
        function alterar(){
            if(verificaSenha($('#msenha').val(),$('#mcsenha').val()) == true || verificaTipo($('#mcmb-tipo').val()) == true){

            }else{
                $.ajax({
                    type: "POST",
                    url: 'usuario/alterar',
                    data:{'tipo': $('#mcmb-tipo').val(),
                        'senha': $('#msenha').val(),
                        'usuario': $('#musuario').val(),},
                    success: function(data){
                        if ( data == 1){
                            limpar();
                            swal({
                                title: "OK",
                                text: "SENHA ALTERADA",
                                type:"success",
                                showCancelButton: false,
                                ConfirmButtonColor: "#54DD74",
                                ConfirmButtonText: "OK",
                                closeOnConfirm: true,
                                closeOnCancel: false,
                            },
                            function(isConfirm){
                                if (isConfirm) {
                                    $('#tableusu').bootstrapTable('refresh');
                                }
                            });
                            $('#alteracao').Modal('hidden=true');
                        }else{
                            swal({
                                title:"Atenção",
                                text: "Erro na alteração, verifique!",
                                type: 'error',
                                showCancelButton: false,
                                ConfirmButtonColor: "#54DD74",
                                ConfirmButtonText: "OK",
                                closeOnConfirm: false,
                                closeOnCancel: false
                                
                            });
                        }
                    },
                    beforeSend: function(){
                        swal({
                            title: "Aguarde!",
                                text: "Carregando...",
                                showConfirmButton: false
                        });        
                    },
                    error: function(){
                        alert('erro');
                    }
                })
            }
        };
        function desativa_usuario(usuario){
            if(verificaUsuLogin(usuario)){

            }else{
                swal({
                    title: 'Atenção',
                    text: 'Gostaria de Desativar este usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    ConfirmButtonColor: "#DD6B55",
                    ConfirmButtonText: "Sim",
                    CancelButtonText: "Não",
                    closeOnConfirm: false,
                    closeOnCancel: true },

                    function(isConfirm){
                        $.ajax({
                            url: base_url + "usuario/desativar",
                            type: "POST",
                            data: {'usuario' : usuario},
                            success: function(data){
                                if (data == 1) {
                                    swal({
                                        title: "OK!",
                                        text: "Usuário DESATIVADO!",
                                        type: "success",
                                        showCancelButton: false,
                                        ConfirmButtonColor: "#54DD74",
                                        ConfirmButtonText: "OK!",
                                        CancelButtonText:"",
                                        closeOnConfirm: true,
                                        closeOnCancel: false},
                                        function(isConfirm){
                                            if (isConfirm){
                                                $('#tableusu').bootstrapTable('refresh');
                                            }
                                        }
                                    );
                                }else{
                                    swal({
                                        title: "OK!",
                                        text: "erro na desativação, verifique",
                                        type: "error",
                                        showCancelButton: false,
                                        ConfirmButtonColor: "#54DD74",
                                        ConfirmButtonText: "OK!",
                                        CancelButtonText:"",
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    });
                                }
                            },
                            beforeSend: function () {
                                swal({
                                    title: "Aguarde!",
                                    text: "Gravando dados...",
                                    imageUrl: "assets/img/alertas/loading.gif",
                                    showConfirmButton: false
                                });
                            },
                            error: function(data_error){
                                sweetAlert("Atenção", "Erro ao gravar os dados!", "error");
                            }
                        })
                    }
                )
            }
        };
        function reativa_usuario(usuario){
            swal({
                title: 'Atenção',
                text: 'Gostaria de Reativar este usuario?',
                type: 'warning',
                showCancelButton: true,
                ConfirmButtonColor: "#DD6B55",
                ConfirmButtonText: "Sim",
                CancelButtonText: "Não",
                closeOnConfirm: false,
                closeOnCancel: true },

                function(isConfirm){
                    $.ajax({
                        url: base_url + "usuario/reativar",
                        type: "POST",
                        data: {'usuario' : usuario},
                        success: function(data){
                            if (data == 1) {
                                swal({
                                    title: "OK!",
                                    text: "Usuário REATIVADO!",
                                    type: "success",
                                    showCancelButton: false,
                                    ConfirmButtonColor: "#54DD74",
                                    ConfirmButtonText: "OK!",
                                    CancelButtonText:"",
                                    closeOnConfirm: true,
                                    closeOnCancel: false},
                                    function(isConfirm){
                                        if (isConfirm){
                                            $('#tableusu').bootstrapTable('refresh');
                                        }
                                    }
                                );
                            }else{
                                swal({
                                     title: "OK!",
                                    text: "erro na reativação, verifique",
                                    type: "error",
                                    showCancelButton: false,
                                    ConfirmButtonColor: "#54DD74",
                                    ConfirmButtonText: "OK!",
                                    CancelButtonText:"",
                                    closeOnConfirm: false,
                                    closeOnCancel: false
                                });
                            }
                        },
                        beforeSend: function () {
                            swal({
                                title: "Aguarde!",
                                text: "Gravando dados...",
                                imageUrl: "assets/img/alertas/loading.gif",
                                showConfirmButton: false
                            });
                        },
                        error: function(data_error){
                            sweetAlert("Atenção", "Erro ao gravar os dados!", "error");
                        }
                    })
                }
            )
        };
        function verifica(){
            $.ajax({
                type: "POST",
                url: 'usuario/verificaUsu',
                data:{'usuario': $('#usuario').val()},
                success: function(data){
                    if ( data == 1){
                        swal({
                            title: "OK",
                            text: "Usuário já existe!!",
                            type:"error",
                            showCancelButton: false,
                            ConfirmButtonColor: "#54DD74",
                            ConfirmButtonText: "OK",
                            closeOnConfirm: true,
                            closeOnCancel: false,  
                        });
                        $('#btnlimpar').click();
                        $('#usuario').focus();
                    }else{
                        swal.close();
                    }
                },
                beforeSend: function(){
                    swal({
                         title: "Aguarde!",
                         text: "Carregando...",
                         imageUrl: "assets/img/gifs/preloader.gif",
                         showConfirmButton: false
                    });        
                },
                error: function(){
                    alert('Unexpected error.');
                }
            });
        };

        function verificaSenha($senha,$confirmaSenha){
            if ($senha != $confirmaSenha ){
                swal({
                    title: "Atenção!",
                    text: "Senhas Incompatíveis!!",
                    type:"error",        
                });
                return true;
            }else{
                return false;
            }
        };

        function verificaTipo($tipo){
            if($tipo == 1){
                swal({
                    title: "Atenção!",
                    text: "Tipo Inválido!!",
                    type:"error",        
                });
                return true;
            }else{
                return false;
            }
        };

        function limpar(){
            $('#musuario').val('');
            $('#msenha').val('');
            $('#mcsenha').val('');
            alert(document.getElementById('mcmb-tipo').value = "Selecione o Tipo");
            document.getElementById('mcmb-tipo').value = "teste";
            //$('#mcmbtipo option_contains(Selecione o Tipo)').attr('selected', true);        

            //document.getElementById('mcmb-tipo').value = "1";
            
        };

        function verificaUsuLogin($usuario){
            var userLogin = "<?= $this->session->userdata('usuario') ?>";
            if($usuario == userLogin){
                swal({
                    title: "Atenção!",
                    text: "Você não pode se deletar!!",
                    type:"error",        
                });
                return true;
            }else{
                return false;
            }
            
        };
    </script>

    <!-- Modal para alteração de usuarios será chamado em na função busca_usuario -->
    <div class="modal fade" id="alteracao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    <h4 class="modal-title" id="myModalLabel">Alterar dados do usuário</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-xs-6 col-md-6">
                            <label class="control-label">Usuario:</label>
                            <input  name = "musuario" id="musuario" class="form-control" placeholder="Usuário"  type="text" readonly>
                        </div>

                        <div class="form-group col-xs-6 col-md-6">
                            <label class="control-label">Senha:</label>
                            <input type="password" class ="form-control" placeholder="Senha" name="msenha" id="msenha" required>
                        </div>
                        <div class="form-group col-lg-6">      
                                <label for="inputPassword" class="control-label">Confirme a Senha</label>      
                                <input type="password" class="form-control" placeholder="Informe sua senha" 
                                 name="mcsenha" id="mcsenha" data-minlength="6" required>      
                        </div>
                         <div class="form-group col-lg-6">
                            <label for="textUsario" class="control-label">Tipo:</label>
                            <select name = "mcmb-tipo" id= "mcmb-tipo"
                                class = "form-control selectpicker" data-container = "body" data-width = "100%" required>
                                <option value = "1" id = "teste" name = "maisTeste">Selecione o Tipo</option>
                                <option>Administrador</option>
                                <option value="3">Comum</option>
                            </select>
                        </div>                       
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #A9A9A9;">
                    <button type="submit" class="btn btn-lg btn-primary" onclick="alterar();">Alterar</button>
                    <button type="submit" class="btn btn-lg btn-info" data-dismiss="modal">Sair</button>
                </div>
            </div>
        </div>
    </div>
</html>
<!-- <script type="text/javascript">
    document.getElementById('mcmb-tipo').value = "3";
</script> -->