var count = document.getElementById("count");
var qstAns = document.getElementById("QstAns");
var request = new Map;
function getRequest(){
    if(request.size!==0){
        request = new Map;
    }
    let qst = new Map;
    let ans = new Map;
    for (let i = 0; i < qstAns.childNodes.length; i++) {
        qst = qstAns.childNodes[i].childNodes[0].childNodes[0].value
        ans = qstAns.childNodes[i].childNodes[1].childNodes[0].value
        request.set(qst,ans);
    }
}
function mapToJson(map) {
    return JSON.stringify([...map]);
}
function initTest(key){
    getRequest()
    requestJson = mapToJson(request)
    console.log(requestJson)

    fetch("../endpoints/initTest.php",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body:JSON.stringify({map:requestJson,testKey:key})
    }).then((response)=>response.json())
        .then((data)=>{
            console.log(data)
            if(data["status"]==="failed"){
                alert("Presiahli ste limit 6 otazok")
                document.getElementById("vytvorit").disabled = true;
            }else{
                alert("Pridali ste otazku")
                setTimeout(function () {
                    window.location.href = "../addTest.php?test_code="+key;
                }, 500)
            }
        })
}
count.addEventListener("change",()=>{
    if(qstAns.firstChild){
        while (qstAns.firstChild) {
            qstAns.removeChild(qstAns.firstChild);
        }
    }
    for (let i = 0; i < count.value; i++) {
        var inptQ = document.createElement("textarea");
        inptQ.setAttribute("onkeyup","this.value = this.value.replace(',', '')")
        inptQ.setAttribute("id","inptQ" +(i+1))
        inptQ.setAttribute("name","inptQ" +(i+1))
        inptQ.setAttribute("rows","5")
        inptQ.setAttribute("cols","20")
        inptQ.setAttribute("maxlength","100")
        inptQ.setAttribute("placeholder","Otázka "+(i+1))

        var inptA = document.createElement("textarea");
        inptA.setAttribute("onkeyup","this.value = this.value.replace(',', '')")
        inptA.setAttribute("id","inptA" +(i+1))
        inptA.setAttribute("name","inptA" +(i+1))
        inptA.setAttribute("rows","5")
        inptA.setAttribute("cols","20")
        inptA.setAttribute("maxlength","100")
        inptA.setAttribute("placeholder","Odpoved "+(i+1))

        var row = document.createElement("div")
        row.setAttribute("class","row justify-content-center")

        var col = document.createElement("div")
        col.setAttribute("class","col-sm-5")
        col.appendChild(inptQ)
        row.appendChild(col)

        var col = document.createElement("div")
        col.setAttribute("class","col-sm-5")
        col.appendChild(inptA)
        row.appendChild(col)

        qstAns.appendChild(row)
    }
})