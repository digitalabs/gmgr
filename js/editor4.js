var margin = 
	{
		top: 200,
		right: 50,
		bottom: 200,
		left: 570 //1370
	},
	
	customNodes = new Array(),
	layer_wider_label = new Array(),
	label_w = 70,
    branch_w = 70,
	m = [100, 500, 100, 500],
	realWidth = window.innerWidth,
	realHeight = window.innerHeight,
	h = realHeight,// -m[0] -m[2],
	w = realWidth,// -m[0] -m[0], 
	width = 500,// - margin.right - margin.left,//width = 3700 - margin.right - margin.left,
	height = 500 - margin.top - margin.bottom;//height = 2050 - margin.top - margin.bottom;

	var root = (function () {
			var json = null;
			$.ajax({
				'async': false,
				'global': false,
				'url': "/GMGR/json_files/tree.json",
				'dataType': "json",
				'success': function (data) {
					json = data;
				}
			});
			return json;
			})(); 

	var i = 0,
		duration = 550,
		rectW = 80,
		rectH = 17,
		ms;

	var tree = d3.layout.tree().nodeSize([120, 50]);

	var diagonal = d3.svg.diagonal()
					 .projection(function (d) {
						return [d.x + rectW / 2, (height-d.y) + rectH / 2];
					 });

	var svg = d3.select("#graphDiv").append("svg")
				.attr("width", width + margin.right + margin.left)
				.attr("height", height + margin.top + margin.bottom)
				.append("g")
				.attr("class","drawarea")
				.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
				
	//d3.select("#generate").on("click", writeDownloadLink);
	
	var ms = document.getElementById('maxStep').value;
	var tmpNodes = d3.layout.tree().size([450, 300]).nodes(root);
	
//function graph2()
//{
	
	root.x0 = function(d) { return d.x; };//0;
	root.y0 = function(d) { return height - d.y; };//height / 2;
	//alert('success');
	root.depth = parseInt(root.layer);
	customNodes.push(root);
	prepareNodes(root.children);
	updateNodesXOffset()
	
	//root.children.forEach(collapse);
	update(root);

	d3.select("#graphDiv").style("height", "660px");
	
	//show_svg_code();
	
	
	
	function collapse(d) 
	{
		if (d.children) 
		{
			d._children = d.children;
			d._children.forEach(collapse);
			d.children = null;
		}
	}
	
	
	/*d3.select("svg")
	  .call(d3.behavior.zoom()
      .scaleExtent([0.5,5])
      .on("zoom", zoom));*/
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

function prepareNodes(nodes) {
    nodes.forEach(function(node) {
		//alert('try');
        prepareNode(node);
        if (node.children) {
            prepareNodes(node.children);
        }
    });
}

function prepareNode(node) {
    node.y = getNodeY(node.id);
		//.on("click", click);
    //fake element to calculate labels area width.
    var fakeTxtBox = svg.append("svg:text")
            .attr("id", "fakeTXT")
            .attr("text-anchor", "right")
            .text(node.name + " : " + node.gid)
			//.on("click", click(node));
    var this_label_w = fakeTxtBox.node().getComputedTextLength();
    svg.select("#fakeTXT").remove();
    if (layer_wider_label[node.layer] == null) {
        layer_wider_label[node.layer] = this_label_w;
    } else {
        if (this_label_w > layer_wider_label[node.layer]) {
            layer_wider_label[node.layer] = this_label_w;
        }
    }
//                node.x = nodex;
    //x will be set
    node.depth = parseInt(node.layer);
    customNodes.push(node);
	//node.on("click", click(node));
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

function update(source) 
{
    // Compute the new tree layout.
    var nodes = tree.nodes(root).reverse(),
        links = tree.links(nodes,function (d) {
			return d.id || (d.id = ++i)
			});

    // Normalize for fixed-depth.
    nodes.forEach(function (d) {
        d.y = d.depth * 100;
    });
	
    // Update the nodes…
    var node = svg.selectAll("g.node")
        .data(nodes, function (d) {
			return d.id || (d.id = ++i);
    });
	//if(node.depth <= ms){
    // Enter any new nodes at the parent's previous position.
    var nodeEnter = node.enter()
						.append("g")
						.attr("class", "node")
						.attr("transform", function (d) {
							return "translate(" + source.x0 + "," + source.y0 + ")";
						})
						.on("click", click);
	
	//var txtBox = nodeEnter.append("text")
    
	//var txtW = txtBox.node().getComputedTextLength();
	/*nodeEnter.append("rect","text")
			 .attr("width", rectW)
			 .attr("height", rectH)
			 .attr("stroke", "black")
			 .attr("stroke-width", 0.5)
			 .attr("rx", 4)
             .attr("ry", 4)
			 .style("fill", function (d) {
				return d._children ? "#0099FF" : "#fff";
			 });*/

			
    nodeEnter.append("text")
			 .attr("x", rectW / 2)
			 .attr("y", (rectH-40) / 2)
			 .attr("stroke", node.current ? "#ffffff" : node.children ? "#ffffff" : "#000000")
			 .attr("stroke-width", 0.5)
			 //.attr("stroke", "white")
			 .attr("dy", ".15em")
			 .attr("text-anchor", "middle")
			 .text(function (d) {
				return d.name;
			 });
	
	nodeEnter.append("text")
			 .attr("x", rectW / 2)
			 .attr("y", (rectH-10) / 2)
			 .attr("stroke", node.current ? "#ffffff" : node.children ? "#ffffff" : "#000000")
			 .attr("stroke-width", 0.5)
			 //.attr("stroke", "white")
			 .attr("dy", ".15em")
			 .attr("text-anchor", "middle")
			 .text(function (d) {
				return "("+d.gid+")";
			 });
	
			 
    // Transition nodes to their new position.
    var nodeUpdate = node.transition()
						 .duration(duration)
						 .attr("transform", function (d) {
							return "translate(" + d.x + "," + (height-d.y) + ")";
						 });

    nodeUpdate.select("rect","text")
			  .attr("width", rectW)
			  .attr("height", rectH)
			  .attr("stroke", "black")
			  .attr("stroke-width", 0.5)
			  .attr("rx", 4)
              .attr("ry", 4)
			  .style("fill", function (d) {
				return d._children ? "#0099FF" : "#fff";
			  });

    nodeUpdate.select("text")
			  .style("fill-opacity", 1);

    // Transition exiting nodes to the parent's new position.
    var nodeExit = node.exit()
					   .transition()
					   .duration(duration)
					   .attr("transform", function (d) {
							return "translate(" + source.x + "," + (height-source.y) + ")";
					   })
					   .remove();

    nodeExit.select("rect")
			.attr("width", rectW)
			.attr("height", rectH)
			.attr("stroke", "black")
			.attr("stroke-width", 1);

    nodeExit.select("text");
	
    // Update the links…
    var link = svg.selectAll("path.link")
				  .data(links, function (d) {
					return d.target.id;
				  });
				  //.attr("class", function(d) {
                  //   return d.method === "true" ? "link method" : "link"});

    // Enter any new links at the parent's previous position.
    link.enter().insert("path", "g")
		//.attr("class", "link")
		//.style("stroke", function(d) { return d.method === "true" ? "#33CC33" : "#FF9900"; })
        .attr("class", function(d) {
                return d.method === "true" ? "link method" : "link"
            })
        .attr("x", rectW / 2)
        .attr("y", rectH / 2)
        .attr("d", function (d) {
			var o = {
				x: source.x0,
				y: (height-source.y0)
			};
			return diagonal({
				source: o,
				target: o
			});
		});

    // Transition links to their new position.
    link.transition()
        .duration(duration)
		.attr("class", function(d) {
                return d.method === "true" ? "link method" : "link"
            })
        .attr("d", diagonal);

    // Transition exiting nodes to the parent's new position.
    link.exit().transition()
        .duration(duration)
        .attr("d", function (d) {
			var o = {
				x: source.x.id,
				y: (height-source.y0)
			};
			return diagonal({
				source: o,
				target: o
			});
		})
		.attr("class", function(d) {
                return d.method === "true" ? "link method" : "link"
            })
        .remove();

    // Stash the old positions for transition.
    nodes.forEach(function (d) {
        d.x0 = d.y;
        d.y0 = d.x;
    });
	//}
}

// Toggle children on click.
function click(d) 
{
    if (d.children) 
	{
        d._children = d.children;
        d.children = null;
    } 
	else 
	{
        d.children = d._children;
        d._children = null;
    }
	
    update(d);
	
	document.getElementById('gid').innerHTML = d.gid;
    document.getElementById('gname').innerHTML = d.name;
	document.getElementById('gmethod').innerHTML = d.methodname;
	document.getElementById('gmtype').innerHTML = d.methodtype;
	document.getElementById('gdate').innerHTML = d.date;
	document.getElementById('gcountry').innerHTML = d.country;
    document.getElementById('gloc').innerHTML = d.location;
	document.getElementById('gcname').innerHTML = d.cname;
	document.getElementById('gref').innerHTML = d.ref;
    document.getElementById('gpid1').innerHTML = d.gpid1;
    document.getElementById('gpid2').innerHTML = d.gpid2;
}

function zoom() 
{
    var scale = d3.event.scale,
        translation = d3.event.translate,
        tbound = -h * scale,
        bbound = h * scale,
        lbound = (-w + m[1]) * scale,
        rbound = (w - m[3]) * scale;
		
    // limit translation to thresholds
    translation = [
        Math.max(Math.min(translation[0], rbound), lbound),
        Math.max(Math.min(translation[1], bbound), tbound)
    ];
	
    d3.select(".drawarea")
      .attr("transform", "translate(" + translation + ")" + " scale(" + scale + ")");
}

function submit_download_form(output_format)
{
	// Get the d3js SVG element
	var tmp = document.getElementById("graphDiv");
	var svg = tmp.getElementsByTagName("svg")[0];
	// Extract the data as SVG text string
	var svg_xml = (new XMLSerializer).serializeToString(svg);

	// Submit the <FORM> to the server.
	// The result will be an attachment file to download.
	var form = document.getElementById("svgform");
	form['output_format'].value = output_format;
	form['data'].value = svg_xml ;
	form.submit();
}

function show_svg_code()
{
	// Get the d3js SVG element
	var tmp  = document.getElementById("graphDiv");
	var svg = tmp.getElementsByTagName("svg")[0];

	// Extract the data as SVG text string
	var svg_xml = (new XMLSerializer).serializeToString(svg);

	//Optional: prettify the XML with proper indentations
	svg_xml = vkbeautify.xml(svg_xml);

	// Set the content of the <pre> element with the XML
	$("#svg_code").text(svg_xml);

	//Optional: Use Google-Code-Prettifier to add colors.
	prettyPrint();
}


