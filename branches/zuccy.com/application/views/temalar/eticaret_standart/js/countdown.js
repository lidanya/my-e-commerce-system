function countdown(obj) {
    this.obj = obj;
    this.Div = "clock";
    this.BackColor = "white";
    this.ForeColor = "black";
    this.CurrentDate = "12/31/2020 5:00:00 AM"; //rramaiah: pass in current date (down to the second) so that it can be set by server
    this.TargetDate = "12/31/2020 5:00 AM";
    this.DisplayFormat = "%%D%% gün %%H%%:%%M%%:%%S%%";
    this.CountActive = true;
    this.Prefix = "";
    this.DisplayStr;

    this.Calcage = cd_Calcage;
    this.CountBack = cd_CountBack;
    this.Setup = cd_Setup;
}

function cd_Calcage(secs, num1, num2, compact) //rramaiah: "compact" means do not pad with zeros
{
    s = ((Math.floor(secs / num1)) % num2).toString();
    if (!compact) {
        if (s.length < 2) s = "0" + s;
    }
    return (s);
}
function cd_CountBack(secs) {
    if (secs <= 0) {
        this.CountActive = false;
        this.DisplayStr = '';

        if (window.location.toString().indexOf("Basket", 0) > 0) {
            //mdernek --BasketService ile senkron değildi. Serviste ürün olduğu halde burası 0 olup redirect oluyordu. Loopa giriyordu. Kaldırıldı. 16.12.2010 16:42
            //window.location = window.location + "?msg=timeout" ;
        }
    }
    else {
        var days = this.Calcage(secs, 86400, 100000, true); //rramaiah: do not pad days with zeros
		if (secs < 3600) {
	        	this.DisplayStr = this.Prefix + " " + this.DisplayFormat.replace(/%%D%% gün %%H%%:/g, '');
			    this.DisplayStr = this.DisplayStr.replace(/%%M%%/g, this.Calcage(secs, 60, 60));
				this.DisplayStr = this.DisplayStr.replace(/:/g, ' dakika ');
                this.DisplayStr = this.DisplayStr.replace(/%%S%%/g, this.Calcage(secs, 1, 60) + ' saniye');
        } else {
		
		if (days < 1) {
            //this.DisplayStr = this.DisplayStr.replace(/Days/g, 'gün');
			    this.DisplayStr = this.Prefix + " " + this.DisplayFormat.replace(/%%D%% gün/g, '');
				this.DisplayStr = this.DisplayStr.replace(/%%H%%/g, this.Calcage(secs, 3600, 24));
				this.DisplayStr = this.DisplayStr.replace(/:/g, ' saat ');
			    this.DisplayStr = this.DisplayStr.replace(/%%M%%/g, this.Calcage(secs, 60, 60));
                this.DisplayStr = this.DisplayStr.replace(/saat %%S%%/g, ' dakika');
        } else {
		
        this.DisplayStr = this.Prefix + " " + this.DisplayFormat.replace(/%%D%%/g, days);
        this.DisplayStr = this.DisplayStr.replace(/%%H%%/g, this.Calcage(secs, 3600, 24));
		this.DisplayStr = this.DisplayStr.replace(/:%%M%%/g, ' saat');
        this.DisplayStr = this.DisplayStr.replace(/:%%S%%/g, '');
        }
    }
}
    var tmpDiv = document.getElementById(this.Div);
    if (tmpDiv) tmpDiv.innerHTML = this.DisplayStr;
    if (this.CountActive) setTimeout("if (" + this.obj + ") " + this.obj + ".CountBack(" + (secs - 1) + ")", 990);
}
function cd_Setup() {
    var dthen = new Date(this.TargetDate);
    var dnow = new Date(this.CurrentDate);
    ddiff = new Date(dthen - dnow);
    gsecs = Math.floor(ddiff.valueOf() / 1000);
    this.CountBack(gsecs);
}

function myCd(bas, bit, myid) {
    var myGCD = new countdown(myid);
    myGCD.Div = myid;
    myGCD.Prefix = "";
    myGCD.CurrentDate = bas;
    myGCD.TargetDate = bit;
    myGCD.Setup();
}