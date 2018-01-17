function aktywuj (){
    var check = document.getElementById('checkActive');
    var activate = document.getElementById('activate');
    if(check.checked) {
        activate.style.visibility = 'visible';
    }
    else {
        activate.style.visibility = 'hidden';
    }
}

function usuń_komunikat() {
    document.getElementById('imie').value = "";
    document.getElementById('komunikat').value = "";
}

function checkActive() {
    var activate = document.getElementById('activate');
    if (activate.style.visibility == 'visible') {
        return true;
    }
    else {
        return false;
    }
}

function wyślij_komunikat() {
    var komunikat = document.getElementById('komunikat').value;
    var imie = document.getElementById('imie').value;
    var nazwa_bloga = document.getElementById('nazwa_bloga').value;
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
            displaykomunikats(this.responseText);
        scrollkomunikatorToTheBottom();
    }
    xml.open("GET","ajax.php?imie="+imie+"&komunikat="+komunikat+"&nazwa_bloga="+nazwa_bloga,true);
    xml.send();
}

var interval;

function włącz() {
    if (document.getElementById('checkActive').checked) {
        interval = setInterval(weźKomunikaty, 2000);
    }
    else {
        clearInterval(interval);
    }
}

function weźKomunikaty() {
    var nazwa_bloga = document.getElementById('nazwa_bloga').value;
    var xml = new XMLHttpRequest();
    xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
            document.getElementById('komunikator').value=this.responseText;
        document.getElementById('komunikator').scrollTop = document.getElementById('komunikator').scrollHeight;
    }
    xml.open("GET","ajax.php?imie=&komunikat=&nazwa_bloga="+nazwa_bloga, true);
    xml.send();
}
