var orientation = {
  "": {
    size: [width, height],
    x: function(node) { return node.x; },
    y: function(node) { return height - node.y; }
  }, 
};

var data = (function () {
    var jason = null;
    $.ajax({
        'async': false,
        'global': false,
        'url': "/../GMGR/json_files/tree.json",
        'dataType': "json",
        'success': function (data) {
            jason = data;
        }
    });
    return jason;
})(); 

var realWidth = window.innerWidth;
var realHeight = window.innerHeight;
var margin = {top: 950, right: 50, bottom: 200, left: 1500},
	m = [100, 500, 100, 500],     
    width = 2000 - margin.left - margin.right,
    height = 5050 - margin.top - margin.bottom,
    h = realHeight -m[0] -m[2],
	rectW = 200,
    rectH = 100,
    i = 0,
    w = realWidth -m[0] -m[0];  

var customNodes = new Array(),
        tmpNodes,
        label_w = 8,
        branch_w = 30,
        layer_wider_label = new Array(),
        depencencyChart;
		
//function graph2() {
	var ms = document.getElementById('maxStep').value;
	//alert(ms);
	
	$('#graphDiv').css({
		 '-moz-transform':'rotate(270deg)',
		 '-webkit-transform':'rotate(270deg)',
		 '-o-transform':'rotate(270deg)',
		 '-ms-transform':'rotate(270deg)',
		 'transform':'rotate(270deg)'
	});
	
    tmpNodes = d3.layout.tree().size([1300, 500]).nodes(data)
				 //.on("click", click)	;//;
	
					
	//Create a svg canvas
    depencencyChart = d3.select("#graphDiv").append("svg:svg")
			//.data(d3.entries(orientation))
            .attr("width", 6000)
            .attr("height", 4900)
            .append("svg:g")
			.attr("class","drawarea")
            .attr("transform", "translate(100, 2000)") // shift everything to the right
			
			

    var fakeTxtBox = depencencyChart.append("svg:text")
            .attr("id", "fakeTXT")
            .attr("text-anchor", "left")
            .text(data.name + "(" + data.gid + ")")
			//.on("click", click);
    layer_wider_label[0] = fakeTxtBox.node().getComputedTextLength();
    depencencyChart.select("#fakeTXT").remove();
    data.y = getNodeY(data.id);
    data.x = 0;
			
    data.depth = parseInt(data.layer);
    customNodes.push(data);//.on("click", click);	
    prepareNodes(data.children);//.on("click", click(data));
    //align nodes.
    updateNodesXOffset()

    if(ms==""||ms==" "||ms=="All")
		drawChart2();
	else
		drawChart(ms);
	
	function collapse(data) {
        if (data.children) {
            data._children = data.children;
            data._children.forEach(collapse);
            data.children = null;
        }
    }

    data.children.forEach(collapse);
//}

function updateNodesXOffset(){
    var x_offsets = new Array();
    x_offsets[0] = 0;
    customNodes.forEach(function(node) {
        node.x = 0;
        if (node.layer > 0) {
            node.x = x_offsets[node.layer - 1] + layer_wider_label[node.layer - 1] + branch_w;
            x_offsets[node.layer] = node.x;
        }
    });
}

function getNodeY(id) {
    var ret = 0;
    tmpNodes.some(function(node) {
        if (node.id === id) {
            //return x:d3.tree has a vertical layout by default.
            //ret = node.x
			ret = node.x;
            return;
        }
    })
    return ret;
}

function prepareNodes(nodes) {
    nodes.forEach(function(node) {
		//alert('try');
        prepareNode(node);
        if (node.children) {
            prepareNodes(node.children);
        }
		//alert(node.name);
    })
	
}

function prepareNode(node) {
    node.y = getNodeY(node.id);
	
    //fake element to calculate labels area width.
    var fakeTxtBox = depencencyChart.append("svg:text")
            .attr("id", "fakeTXT")
            .attr("text-anchor", "right")
            .text(node.name + " : " + node.gid)
		
    var this_label_w = fakeTxtBox.node().getComputedTextLength();
    depencencyChart.select("#fakeTXT").remove();
    if (layer_wider_label[node.layer] == null) {
        layer_wider_label[node.layer] = this_label_w;
    } else {
        if (this_label_w > layer_wider_label[node.layer]) {
            layer_wider_label[node.layer] = this_label_w;
        }
    }
	
    node.depth = parseInt(node.layer);
    customNodes.push(node);
}

function customSpline(d) {
    var p = new Array();
    p[0] = (d.source.x-35) + "," + d.source.y;
    p[3] = (d.target.x-10) + "," + d.target.y;
    var m = (d.source.x + d.target.x)  / 2
    p[1] = m + "," + d.source.y;
    p[2] = m + "," + d.target.y;
    //This is to change the points where the spline is anchored
    //from [source.right,target.left] to [source.top,target.bottom]
               // var m = (d.source.y + d.target.y)/2
               // p[1] = d.source.x + "," + m;
               // p[2] = d.target.x + "," + m;
    //return "M" + p[0] + "C" + p[1] + " " + p[2] + " " + p[3];
	return "M" + p[3] + "C" + p[2] + " " + p[1] + " " + p[0];
	
}

function drawChart(ms) {
	var cnt=0;
	
    customNodes.forEach(function(node) { //alert(node.layer);
		if(node.depth <= ms){
		cnt++;
        var nodeSVG = depencencyChart.append("svg:g")
                .attr("transform", "translate(" + node.x + "," + node.y + ")")
				.enter()
				.append("g")
				
		
		//alert(node.depth);		
        if (node.depth > 0) {
            nodeSVG.append("svg:circle")
                    .attr("stroke", node.children ? "#3191c1" : "#269926")
                    .attr("fill", "#fff")
                    .attr("r", 3)
					
        }
        var txtBox = nodeSVG.append("svg:text")
				//.on("click", click)
				//.attr("class", "name")
                .attr("dx", 8)
                .attr("dy", 4)
                //.attr("fill", node.current ? "#ffffff" : node.children ? "#226586" : "#269926")
				//.attr("fill", node.current ? "#ffffff" : node.children ? "#ffffff" : "#ffffff")
                .text(node.name)
				//.style("stroke", "black")
				//.on("click", click)
				var txtW = txtBox.node().getComputedTextLength();
				nodeSVG.insert("rect", "text")
                    .attr("fill", node.children ? "#3191c1" : "#3191c1")
					.attr("stroke", "black")
                    .attr("width", txtW + 8)
                    .attr("height", "20")
                    .attr("y", "-12")
                    .attr("x", "5")
                    .attr("rx", 4)
                    .attr("ry", 4)
				
				//.on("click", click(node))
        
       /* if (node.current) {
            nodeSVG.insert("rect", "text")
                    .attr("fill", node.children ? "#FF4D4D" : "#269926")
                    .attr("width", txtW + 8)
                    .attr("height", "20")
                    .attr("y", "-12")
                    .attr("x", "5")
                    .attr("rx", 4)
                    .attr("ry", 4)
        }*/
		
		//if(cnt <= ms){
        if (node.children) {
            node.x = node.x + txtW + 20;
            //prepare links;
            var links = new Array();
            node.children.forEach(function(child) {
                var st = new Object();
                st.source = node;
//                        st.parent = node;
                st.target = child;
                st.method = child.method;
                links.push(st);
            })
			.on("click", click);
			
            //draw links (under nodes)
            depencencyChart.selectAll("pathlink")
                    .data(links)
                    .enter().insert("svg:path", "g")
                    .attr("class", function(d) {
                return d.method === "true" ? "link method" : "link"
            })
                    .attr("d", customSpline)
				
					
            //draw a node at the end of the link
            nodeSVG.append("svg:circle")
                    .attr("stroke", "#3191c1")
                    .attr("fill", "#fff")
                    .attr("r", 5.5)
					//.on("click", click(node))
                    .attr("transform", "translate(" + (txtW + 20) + ",0)");
		//}
        }
		//node.on("click", click(node));
		
		}
		
    });
}

function drawChart2(node) {
	
	var cnt=0;
	
    customNodes.forEach(function(node) {
		//if(node.depth <= ms){
		cnt++;
		
        var nodeSVG = depencencyChart.append("svg:g")
                .attr("transform", "translate(" + node.x + "," + (node.y) + ")")
				
				
		if (node.depth > 0) {
            nodeSVG.append("svg:circle")
                    .attr("stroke", node.children ? "#3191c1" : "#269926")
                    .attr("fill", "#fff")
                    .attr("r", 3)
					.attr("cy", 0)
					.attr("cx", -6)
					
        }

        var txtBox = nodeSVG.append("svg:text")
				.attr("class", "name")
                .attr("dx", -19)
                .attr("dy", -5)
                .text("("+node.gid+")")//+" ("+node.gid+")")
				.attr("transform", function(d) {
						return "rotate(90)" 
					})
				.on("click", click)
				
				var txtW = txtBox.node().getComputedTextLength();
			
				nodeSVG.append("svg:text")
				.attr("class", "name")
				.attr("dx", -22)
				.attr("dy", -24)
				.text(node.name)
				.attr("transform", function(d) {
						return "rotate(90)" 
					})
				
		//if(cnt <= ms){
        if (node.children) {
            node.x = node.x + txtW + 20;
			
            //prepare links;
            var links = new Array();
            node.children.forEach(function(child) {
                var st = new Object();
                st.source = node;
                st.target = child;
                st.method = child.method;
                links.push(st);
				
            })
			
			
            //draw links (under nodes)
            depencencyChart.selectAll("pathlink")
                    .data(links)
                    .enter().insert("svg:path", "g")
                    .attr("class", function(d) {
                return d.method === "true" ? "link method" : "link"
            })
                    .attr("d", customSpline)
					
					
			//draw a node at the end of the link
            nodeSVG.append("svg:circle")
                    .attr("stroke", "#3191c1")
                    .attr("fill", "#fff")
                    .attr("r", 5.5)
                    .attr("transform", "translate(" + (txtW+10) + ",0)")
					.attr("cx", -30);
			//}
					
        }
		
		//}
		
    });
}

function zoom() {
    var scale = d3.event.scale,
        translation = d3.event.translate,
        tbound = -h * scale,
        bbound = h * scale,
        lbound = -w * scale,//lbound = (-w - m[1]) * scale,
        rbound = w * scale;//rbound = (w - m[3]) * scale;
    // limit translation to thresholds
	
    translation = [
        Math.max(Math.min(translation[0], rbound), lbound),
        Math.max(Math.min(translation[1], bbound), tbound)
    ];
    d3.select(".drawarea")
        .attr("transform", "translate(" + translation + ")" +
              " scale(" + scale + ")");
}

// Toggle children on click.
function click(d) {
    if (data.children) 
	{
        data._children = data.children;
        data.children = null;
    } 
	else 
	{
        data.children = data._children;
        data._children = null;
    }
	
    //update(data);
	//drawChart2(d);
	
	document.getElementById('gid').innerHTML = data.gid;
    document.getElementById('gname').innerHTML = data.name;
	document.getElementById('gmethod').innerHTML = data.method;
	document.getElementById('gmtype').innerHTML = data.methodtype;
	document.getElementById('gdate').innerHTML = data.date;
	document.getElementById('gcountry').innerHTML = data.country;
    document.getElementById('gloc').innerHTML = data.location;
	document.getElementById('gcname').innerHTML = data.cname;
	document.getElementById('gref').innerHTML = data.ref;
    document.getElementById('gpid1').innerHTML = data.gpid1;
    document.getElementById('gpid2').innerHTML = data.gpid2;
}