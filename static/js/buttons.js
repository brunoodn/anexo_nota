//Desativar o botão ao clicar
$("#buttonInsert").click(function() {
    $(this).prop("disabled",true);
  });
  //Reativar o botão após 3 segundos
  $("#buttonInsert").click(function() {
     var btn = this;
      setTimeout(function() {
        btn.disabled =  false; 
      }, 3000);
  });