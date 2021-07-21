class cell{
    constructor(id,title,x,y,width,height,chosen) {
        this._id = id;
        this._title = title;
        this._x = x;
        this._y = y;
        this._width = width;
        this._height = height;
        this._chosen = chosen;
        this._pairedCell = null;
    }

    get pairedCell() {
        return this._pairedCell;
    }

    set pairedCell(value) {
        this._pairedCell = value;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    get title() {
        return this._title;
    }

    set title(value) {
        this._title = value;
    }

    get x() {
        return this._x;
    }

    set x(value) {
        this._x = value;
    }

    get y() {
        return this._y;
    }

    set y(value) {
        this._y = value;
    }

    get width() {
        return this._width;
    }

    set width(value) {
        this._width = value;
    }

    get height() {
        return this._height;
    }

    set height(value) {
        this._height = value;
    }

    get chosen() {
        return this._chosen;
    }

    set chosen(value) {
        this._chosen = value;
    }
}
function showSubmited(key){
    console.log(key)
    fetch("endpoints/getSubmitTest.php",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body:JSON.stringify({key:key})
    }).then((response)=>response.json())
        .then((data)=>{
            console.log(data)
            var myCanvas = document.getElementById("myCanvas")
                rst()
                for (let i = 0; i < data.length; i++) {
                    var questions_data = data[i]["title"].split(",")
                    var asnwers_data = data[i]["answer"].split(",")
                    questions_data.pop();
                    asnwers_data.pop();
                    var height = questions_data.length*100+100;
                    var lbl = document.createElement("label")
                    var div1 = document.createElement("div")
                    div1.appendChild(lbl) 
                    lbl.setAttribute("id","lbl"+(i+1))
                    lbl.setAttribute("for","canvas"+(i+1))
                    myCanvas.appendChild(div1)
                    document.getElementById("lbl"+(i+1)).innerText = "Dosiahnutych ("+parseInt(data[i]["poin"])+"b)";
                    var ltpc = document.createElement("canvas");
                    var div2 = document.createElement("div")
                    div2.appendChild(ltpc) 
                    ltpc.setAttribute("height",height)
                    ltpc.setAttribute("width","650")
                    ltpc.setAttribute("id","canvas"+(i+1))
                    div2.setAttribute("class", "text-center")
                    myCanvas.appendChild(div2);
                    var ltpc = document.getElementById("canvas"+(i+1));
                    var questions = [];
                    var asnwers = [];
                    var pairs = data[i]["pairs"].split("],[");
                    var pairMap = new Map;
                    for (let j = 0; j < pairs.length; j++) {
                        if(j===0){
                            pairMap.set(pairs[j][2],pairs[j][4])
                        }else{
                            pairMap.set(pairs[j][0],pairs[j][2])
                        }
                    }
                    initQuestions(questions,asnwers,questions_data,asnwers_data,JSON.parse(data[i]["shufle"]))
                    drawPaint(questions,asnwers,pairMap);
                    function initQuestions(questions,asnwers,questions_data,asnwers_data,shufle) {
                        console.log(shufle)
                        for (var x = 0; x < questions_data.length; x++) {
                            const quest = new cell(x + 1, questions_data[x], 50, x * 100 + 50, 200, 70, false);
                            const aswer = new cell(x + 1, asnwers_data[x], 400, x * 100 + 50, 200, 70, false)
                            questions.push(quest)
                            asnwers.push(aswer)
                        }
                        if (shufle){
                            for (var x = 0; x < questions.length; x++) {
                                asnwers[x].y = shufle[x].y;
                            }
                        }
                    }
                    function drawPaint(questions,asnwers,pairMap){
                        var context= ltpc.getContext("2d");
                        context.font = "20pt sans-serif";
                        context.beginPath();
                        context.fillText("Otázky",100,30)
                        context.fillText("Odpovede",430,30)
                        context.font = "10pt sans-serif";
                        console.log(pairMap)
                        var pairs = [];
                        for (const [k,v] of pairMap) {
                            if(k == v){
                                pairs.push(k)
                            }
                        }
                        for (var x = 0; x < questions.length ; x++){
                            for (const pairsKey of pairs) {
                                if(pairsKey == questions[x].id){
                                    context.fillStyle = "#00ff00";
                                    break;
                                }else{
                                    context.fillStyle = "#ff0000";
                                }
                            }
                            if(pairs.length===0){
                                context.fillStyle = "#ff0000";
                            }
                            wrapText(context,questions[x].title,questions[x].x+5,questions[x].y+15,questions[x].width,15)
                            context.rect(questions[x].x,questions[x].y,questions[x].width,questions[x].height)
                        }
                        for (var x = 0; x < asnwers.length ; x++){
                            for (const pairsKey of pairs) {
                                if(pairsKey == asnwers[x].id){
                                    context.fillStyle = "#00ff00";
                                    break;
                                }else{
                                    context.fillStyle ="#ff0000";
                                }
                            }
                            if(pairs.length===0){
                                context.fillStyle = "#ff0000";
                            }
                            context.rect(asnwers[x].x,asnwers[x].y,asnwers[x].width,asnwers[x].height)
                            wrapText(context,asnwers[x].title,asnwers[x].x+5,asnwers[x].y+15,asnwers[x].width,15)
                        }
                        context.closePath();
                        context.stroke();
                        pairQuestions(questions,asnwers,context,pairMap);
                    }
                    function pairQuestions(questions,asnwers,context,pairMap){
                        for (let[k,v] of pairMap){
                            for (var x = 0; x < questions.length ; x++){
                                for (var y = 0; y < asnwers.length ; y++){
                                    if(questions[x].id == v && asnwers[y].id == k){
                                        context.beginPath()
                                        context.moveTo(questions[x].x+questions[x].width,questions[x].y+questions[x].height/2)
                                        context.lineTo(asnwers[y].x,asnwers[y].y+asnwers[y].height/2);
                                        context.closePath();
                                        context.stroke();
                                    }
                                }
                            }
                        }
                    }
                }
        })
}
function showTest(key){
    fetch("endpoints/getTest.php",{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body:JSON.stringify({key:key})
    }).then((response)=>response.json())
        .then((data)=>{
            console.log(data)
            var myCanvas = document.getElementById("myCanvas")
            rst()
            for (let i = 0; i < data.length; i++) {
                var questions_data = data[i]["title"].split(",")
                var asnwers_data = data[i]["answer"].split(",")
                questions_data.pop();
                asnwers_data.pop();
                var height = questions_data.length*100+100;
                var lbl = document.createElement("label")
                lbl.setAttribute("id","lbl"+(i+1))
                lbl.setAttribute("for","canvas"+(i+1))
                myCanvas.appendChild(lbl)
                document.getElementById("lbl"+(i+1)).innerText = "Nevyplnena otazka (0b)";
                var ltpc = document.createElement("canvas");
                ltpc.setAttribute("height",height)
                ltpc.setAttribute("width","800")
                ltpc.setAttribute("id","canvas"+(i+1))
                myCanvas.appendChild(ltpc);
                var ltpc = document.getElementById("canvas"+(i+1));
                var questions = [];
                var asnwers = [];
                initQuestions(questions,asnwers,questions_data,asnwers_data)
                drawPaint(questions,asnwers);
                function drawPaint(questions,asnwers){
                    var context= ltpc.getContext("2d");
                    context.font = "20pt sans-serif";
                    context.beginPath();
                    context.fillText("Otázky",100,30)
                    context.fillText("Odpovede",580,30)
                    context.font = "10pt sans-serif";
                    context.fillStyle ="#ff0000";
                    for (var x = 0; x < questions.length ; x++){
                        wrapText(context,questions[x].title,questions[x].x+5,questions[x].y+15,questions[x].width,15)
                        context.rect(questions[x].x,questions[x].y,questions[x].width,questions[x].height)
                    }
                    for (var x = 0; x < asnwers.length ; x++){
                        wrapText(context,asnwers[x].title,asnwers[x].x+5,asnwers[x].y+15,asnwers[x].width,15)
                        context.rect(asnwers[x].x,asnwers[x].y,asnwers[x].width,asnwers[x].height)
                    }
                    context.closePath();
                    context.stroke();
                }
            }
        })
}
function wrapText(context, text, x, y, maxWidth, lineHeight) {
    var words = text.split(' ');
    var line = '';
    for(var n = 0; n < words.length; n++) {
        var testLine = line + words[n] + ' ';
        var metrics = context.measureText(testLine);
        var testWidth = metrics.width;
        if (testWidth > maxWidth && n > 0) {
            context.fillText(line, x, y);
            line = words[n] + ' ';
            y += lineHeight;
        }
        else {
            line = testLine;
        }
    }
    context.fillText(line, x, y);
}
function initQuestions(questions,asnwers,questions_data,asnwers_data) {
    let tmp = [];
    for (var x = 0; x < questions_data.length; x++) {
        const quest = new cell(x + 1, questions_data[x], 50, x * 100 + 50, 200, 70, false);
        const aswer = new cell(x + 1, asnwers_data[x], 550, x * 100 + 50, 200, 70, false)
        questions.push(quest)
        asnwers.push(aswer)
        tmp.push({_x:550,_y:x * 100 + 50,_id:x})
    }
    tmp = shuffle(tmp);
    for (var y = 0; y < tmp.length; y++) {
        for (var x = 0; x < questions.length; x++) {
            if(tmp[y]._id == asnwers[x].id-1){
                asnwers[x].x = tmp[x]._x;
                asnwers[x].y = tmp[x]._y;
            }
        }
    }
}
function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;
    while (0 !== currentIndex) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }
    return array;
}
function rst(){
    var myCanvas = document.getElementById("myCanvas")
    while (myCanvas.firstChild){
        myCanvas.firstChild.remove();
    }
}

