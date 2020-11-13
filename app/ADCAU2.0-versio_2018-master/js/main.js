/**
 * 
 * @param {type} div        Div on se carregarà el resultat de la cridada ajax
 * @param {type} f          Direcció del fitxer php a executar amb GET params
 * @param {type} param      Paràmetre per realitzar alguna tasca auxiliar amb JS
 * @param {type} async      L'execució de AJAX serà sincrona si és false
 * @returns {undefined}
 */
function loadXMLDoc(div,f,param,async)
{
    //Per defecte, l'Ajax s'executa de forma Asynchronous
    param =  typeof param !== 'undefined' ? param : '';
    async =  typeof async !== 'undefined' ? async : true;

    switch (param) {
        case 'ADD':   /* Add a machine to an Issue */
            var num, i=0, chb, hid, total=0, machines = "";
            
            //Loop on all the machines that have appeared after the search
            while(document.getElementById("ch"+i)) {
               if (document.getElementById("ch"+i).checked) {
                   if (total)
                       machines += ",";  //separador
                   //Get the id from the machine from a HIDDEN field named hid1, hid2, ...
                   machines += document.getElementById("hid"+i).value; 
                   total++;
                }        
                i++;
            }
            if (total>0)
                f += "&machines="+machines;
            break;
    }

    //Ajax mitjançant JQUERY
    $.ajax({
        type: "GET", /* Crec que tanmateix, per defecte és GET - xxtoni */
        url: f,
        async: async,
        success : function(resultat) {
    //He de resoldre el problema de la sincronia expressat més avall
            $("#"+div).html(resultat);
        }
    });
}