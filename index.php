<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calculadora de datas</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12" style="margin-top: 20px">

            <div class="col-md-12">
                <div class="page-header">
                    <h2>Calculadora de datas</h2>
                </div>

            </div>

            <form method="post" action="src/Calculate.php" >
                <div class="col-md-6" style="margin-top: 17px">
                    <div class="input-group">
                        <input type="text" name="nome" class="form-control" placeholder="Nome" />
                    </div>
                </div>
                <div class="col-md-3" style="margin-top: 17px">
                    <div class="input-group">
                        <input type="date" name="nascimento" class="form-control" placeholder="Data de nascimento" />
                    </div>
                </div>

                <div class="radio col-md-3" style="margin-top: 17px">
                    <label><input type="radio" name="sexo" value="Masculino"> Masculino</label>&nbsp;&nbsp;
                    <label><input type="radio" name="sexo" value="Feminino"> Feminino</label>
                </div>

                <div class="col-md-10" style="margin-top: 17px" id="hold-intervals">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <button type="button" class="btn btn-primary" id="bt-add-interval" style="cursor: pointer">Adicionar novo intervalo</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 17px">
                        <div class="input-group">
                            <input type="text" name="empresa[]" class="form-control" value="Período 1" />&nbsp;
                            <input type="date" name="inicio[]" class="form-control" placeholder="Início"/>
                            <span class="input-group-addon">-</span>
                            <input type="date" name="fim[]" class="form-control" placeholder="Fim"/>&nbsp;
                            <label style="margin-top: 7px"><input type="checkbox" name="adicional[]" value="0"> Adicional</label>
                        </div>
                    </div>

                </div>
                <div class="col-md-5">
                    <input type="submit" value="Calcular" class="btn btn-primary" style="cursor: pointer" />
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/jquery-3.2.1.min.js" type="application/javascript" ></script>
<script type="application/javascript">

    var count = 1;
    $('#bt-add-interval').on('click', function(){
        $('#hold-intervals').append('<div class="form-group">' +
            '<div class="input-group">' +
            '<input type="text" name="empresa[]" class="form-control" value="Período ' + (count+1) + '"/>&nbsp;<input type="date" name="inicio[]" class="form-control" placeholder="Início"/><span class="input-group-addon">-</span><input type="date" name="fim[]" class="form-control" placeholder="Fim"/>&nbsp; <label style="margin-top: 7px"><input type="checkbox" name="adicional[]" value="' + count +'"> Adicional</label></div></div>');

        count++;
    });

</script>
</body>
</html>





