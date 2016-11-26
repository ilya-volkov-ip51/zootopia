function f(id){
	var elem= document.getElementById(id);
	var value=elem.style.display;
	if(value=="block") elem.style.display="none"; 
	else elem.style.display="block";
}

function prev(a,count){
	a-=10;
	var string=window.location.search;
	if(string.indexOf("animals")!=-1) string=string.substring(string.indexOf("animals")); else
	if(string.indexOf("male")!=-1) string=string.substring(string.indexOf("male")); else
	if(string.indexOf("agestart")!=-1) string=string.substring(string.indexOf("agestart")); else
	if(string.indexOf("locality")!=-1) string=string.substring(string.indexOf("locality")); else
	string="";
	console.log(string);
	if(a>=0)
	location.href = 'http://localhost/zootopia/index.php?i='+a+'&'+string;

}

function next(a,count){
	a-=10;
	a+=20;
	var string=window.location.search;
	if(string.indexOf("animals")!=-1) string=string.substring(string.indexOf("animals")); else
	if(string.indexOf("male")!=-1) string=string.substring(string.indexOf("male")); else
	if(string.indexOf("agestart")!=-1) string=string.substring(string.indexOf("agestart")); else
	if(string.indexOf("locality")!=-1) string=string.substring(string.indexOf("locality")); else
	string="";
	console.log(string);
	if(a<=count)
	location.href = 'http://localhost/zootopia/index.php?i='+a+'&'+string;
}

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#sample').attr('src', e.target.result);
			var elem= document.getElementById("sample");
			elem.style.display="block";
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function getName (str){
    if (str.lastIndexOf('\\')){
        var i = str.lastIndexOf('\\')+1;
    }
    else{
        var i = str.lastIndexOf('/')+1;
    }						
    var filename = str.slice(i);			
    var uploaded = document.getElementById("fileformlabel");
    uploaded.innerHTML = filename;
}

function deleteAd(f) {
    if (confirm("Вы уверены, что хотите удалить объявление?\nЭта операция не восстановима.")) 
       f.submit();
   }