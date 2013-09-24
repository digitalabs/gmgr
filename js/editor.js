var cnt=0;

var realWidth = window.innerWidth;
var realHeight = window.innerHeight;

var margin = {top: 0, right: 220, bottom: 0, left: 20},
	m = [0, 220, 0, 20],
	h = realHeight -m[0] -m[2],
	w = realWidth -m[0] -m[0],
    width  = 500,// - margin.right ,//- margin.right,
    height = 1200 - margin.top - margin.bottom,
	root = "tree.json";
	
var tree = d3.layout.tree()
		     .separation(function(a, b) { return a.parent === b.parent ? 1 : .5; })
		     .children(function(d) { return d.children; })
		     .size([realHeight, w]);

var svg = d3.select("#graphDiv").append("svg:svg")
			.attr("class","svg_container")
		    //.attr("width", width + margin.left + margin.right)
		    //.attr("height", height + margin.top + margin.bottom)
		    .attr("width", w)
			.attr("height", h)
			.style("overflow", "scroll")
	        .append("svg:g")
	        .attr("class","drawarea")
	        .append("svg:g")
		    //.attr("transform", "translate(" + margin.left + "," + margin.top + ")")
		    .attr("transform", "translate(" + m[3] + "," + m[0] + ")")
		    .on("click", function(){update(root);});

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });
		    		
function validate(frm)  
{  
    var ele = frm.elements['feedurl[]'];  
  
    if (! ele.length)  
    {  
        alert(ele.value);  
    }  
  
    for(var i=0; i<ele.length; i++)  
    {  
        alert(ele[i].value);  
    }  
  
    return true;  
}  
  
function add_feed()  
{  
    var div1 = document.createElement('div');  
  
    // Get template data  
    div1.innerHTML = document.getElementById('newlinktpl').innerHTML;  
  
    // append to our form, so that template data  
    // become part of form  
    document.getElementById('newlink').appendChild(div1);  
}  	
	
function handleFileSelect(evt) 
{
    var files = evt.target.files; // FileList object

    // Loop through the FileList
    for (var i = 0, f; f = files[i]; i++) 
    {
       var reader = new FileReader();

       // Closure to capture the file information.
       reader.onload = (function(theFile) 
						{
							return function(e) 
								   {
										// Print the contents of the file
										var span = document.createElement('span');                    
										span.innerHTML = ['<p>',e.target.result,'</p>'].join('');
										document.getElementById('list').insertBefore(span, null);
								   };
						})(f);

       // Read in the file
       reader.readAsText(f,"UTF-8");
       reader.readAsDataURL(f);
    }
}

function pathToFile(str)
{
    var nOffset = Math.max(0, Math.max(str.lastIndexOf('\\'), str.lastIndexOf('/')));
    var eOffset = str.lastIndexOf('.');
    if(eOffset < 0)
    {
        eOffset = str.length;
    }
    return {isDirectory: eOffset == str.length,
            path: str.substring(0, nOffset),
            name: str.substring(nOffset > 0 ? nOffset + 1 : nOffset, eOffset),
            extension: str.substring(eOffset > 0 ? eOffset + 1 : eOffset, str.length)};
}

function graph()
{
	var fullPath = document.getElementById('files').value;
	var fullPath2 = document.getElementById('files');
	var newHTML = "<div class='alert alert-error'><a class='close' data-dismiss='alert'>x</a><strong>Error!</strong></div>";
	var file = pathToFile(fullPath);
	var code = file.name+"."+file.extension;
	var ext = file.extension;
	
	//alert(fullPath);
	if(ext=="json")
	{
		$(document).ready(function() 
		{
			 $('#collapseThree').collapse('show');
		});

		d3.json(code, function(json) 
					  {
							var nodes = tree.nodes(json);
		
	
		var link = svg.selectAll(".link")
					  .data(tree.links(nodes))
					  .enter().append("path")
					  .attr("class", "link")
					  .attr("d", elbow);

		var node = svg.selectAll(".node")
					  .data(nodes)
					  .enter().append("g")
					  .attr("class", "node")
					  .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; })
					  .on("click", click);

		node.append("text")
			.attr("class", "name")
			.attr("x", 8)
			.attr("y", -6)
			.text(function(d) { return d.gid+" : "+d.name; });
			
		//node.append("text")
			//.attr("x", 8)
			//.attr("y", 8)
			//.attr("dy", ".71em")
			//.attr("class", "about location")
			//.text(function(d) { return d.location; });

		});
		//document.getElementById('errDiv').innerHTML = "";
		//var temp = document.getElementById('graphDiv').innerHTML;
		//document.getElementById('graphDiv').innerHTML = "";
		//document.getElementById('graphDiv').innerHTML = temp;
		 
		
		d3.select("svg")
        .call(d3.behavior.zoom()
              .scaleExtent([0.5, 5])
              .on("zoom", zoom));
        update(code); 
	}
	else if(cnt==0)
	{ 
		cnt++;
		//document.getElementById('fileIn').innerHTML = "<div class='alert alert-error'><strong>Error!</strong> Please choose a JSON data</div>"+document.getElementById('fileIn').innerHTML;
		document.getElementById('errDiv').innerHTML = "<div class='alert alert-error'><strong>Error!</strong> Please choose a JSON data</div>";
		//document.getElementById('graphDiv').innerHTML = "";
	}
}

function update(source) 
{

	// Compute the new tree layout.
	var nodes = tree.nodes(root).reverse(),
		links = tree.links(nodes);

	// Normalize for fixed-depth.
	nodes.forEach(function(d) { d.y = d.depth * 180; });

	// Update the nodes…
	var node = svg.selectAll("g.node")
				  .data(nodes, function(d) { return d.id || (d.id = ++i); });

	// Enter any new nodes at the parent's previous position.
	var nodeEnter = node.enter().append("g")  
								.attr("class", "node")
								.attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
								.on("click", click(d));
		 
	nodeEnter.append("circled")
		     .attr("r", 25)
		     .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

	nodeEnter.append("text")
		     .attr("x", function(d) { return d.children || d._children ? 15 : 1; })
		     .attr("dy", ".35em")
		     .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
		     .text(function(d) { return d.name; })
		     .style("fill-opacity", 1e-6);
		   
	// Transition nodes to their new position.
	var nodeUpdate = node.transition()
		                 .duration(duration)
		                 .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

	nodeUpdate.select("circle")
		      .attr("r", 25)
		      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

	nodeUpdate.select("text")
		      .style("fill-opacity", 1);

	// Transition exiting nodes to the parent's new position.
	var nodeExit = node.exit().transition()
		               .duration(duration)
		               .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
		               .remove();

	nodeExit.select("circle")
		    .attr("r", 14);

	nodeExit.select("text")
		    .style("fill-opacity", 1e-6);

	  // Update the links…
	var link = svg.selectAll("path.link")
		          .data(links, function(d) { return d.target.id; });

	// Enter any new links at the parent's previous position.
	link.enter().insert("path", "g")
		        .attr("class", "link")
		        .attr("d", function(d) 
						   {
								var o = {x: source.x0, y: source.y0};
								return diagonal({source: o, target: o});
						   });

	// Transition links to their new position.
	link.transition()
		.duration(duration)
		.attr("d", diagonal);

	// Transition exiting nodes to the parent's new position.
	link.exit().transition()
		.duration(duration)
		.attr("d", function(d) 
				   {
						var o = {x: source.x, y: source.y};
						return diagonal({source: o, target: o});
				   })
		.remove();

	// Stash the old positions for transition.
	nodes.forEach(function(d) 
				  {
						d.x0 = d.x;
						d.y0 = d.y;
				  });
}

// Toggle children on click.
function click(d) 
{
	  //if (d.children) 
	  //{
			//d._children = d.children;
			//d.children = null;
	  //} 
	  //else 
	  //{
			//d.children = d._children;
			//d._children = null;
	  //}
	  //update(d);
	  //window.location="http://www.newlocation.com";
	  document.getElementById('attTab').innerHTML = 
		""
		
							//+"<table class='zebra-striped' width='100%'>"
							+"	<tr> "
							+"		<td style='vertical-align:top;background-color:#CEE7FF' colspan='2'>"
							+"			<font size='2px' style='vertical-align:top;'>Germplasm Information</font><br>"
							+"		</td>"
							+"	</tr>"
							+"	<tr><td><font size='1px'>Name</font></td><td><b>"+d.name+"</td></tr>"
							+"	<tr><td><font size='1px'>ID</font></td><td><b></td></tr>"
							+"	<tr><td><font size='1px'>Method</font></td><td><b></td></tr>"
							+"	<tr><td><font size='1px'>Location</font></td><td><b>"+d.location+"</td></tr>"
							+"	<tr><td><font size='1px'>Date</font></td><td><b></td></tr>"
							+"</table>"
							
							+"<table class='zebra-striped' width='100%'>"
							+"	<tr> "
							+"		<td style='vertical-align:top;background-color:#CEE7FF' colspan='2'>"
							+"			<font size='2px' style='vertical-align:top;'>Germplasm Cross Information</font><br>"
							+"		</td>"
							+"	</tr>"
							+"	<tr><td><font size='1px'>GID</font></td><td><b>"+d.gid+"</td></tr>"
							+"	<tr><td><font size='1px'>Cross Name</font></td><td><b></td></tr>"
							+"	<tr><td><font size='1px'>Method</font></td><td><b></td></tr>"
							+"	<tr><td><font size='1px'>Cross Date</font></td><td><b></td></tr>"
							+"	<tr><td><font size='1px'>Location</font></td><td><b>"+d.location+"</td></tr>"
							//+"</table>";
}

function elbow(d, i) 
{
  return "M" + d.source.y + "," + d.source.x + "H" + d.target.y + "V" + d.target.x + (d.target.children ? "" : "h" + margin.right);
  //return d.source.y + "," + d.source.x;
}

function update(source) {
    var duration = d3.event && d3.event.altKey ? 5000 : 500;
    
    // Compute the new tree layout.
    var nodes = tree.nodes(root).reverse();
    console.warn(nodes)
    
    // Normalize for fixed-depth.
    nodes.forEach(function(d) { d.y = d.depth * 50; });
    
    // Update the nodes…
    var node = vis.selectAll("g.node")
    .data(nodes, function(d) { return d.id || (d.id = ++i); });
    
    // Enter any new nodes at the parent's previous position.
    var nodeEnter = node.enter().append("svg:g")
    .attr("class", "node")
    .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
    .on("click", function(d) { toggle(d); update(d); });
    
    nodeEnter.append("svg:circle")
    .attr("r", function(d){ 
        return  Math.sqrt((d.part_cc_p*1))+4;
    })
    .attr("class", function(d) { return "level"+d.part_level; })
    .style("stroke", function(d){
        if(d._children){return "blue";}
    })    
    ;
    
    nodeEnter.append("svg:text")
    .attr("x", function(d) { return d.children || d._children ? -((Math.sqrt((d.part_cc_p*1))+6)+this.getComputedTextLength() ) : Math.sqrt((d.part_cc_p*1))+6; })
    .attr("y", function(d) { return d.children || d._children ? -7 : 0; })
    .attr("dy", ".35em")
    .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
    .text(function(d) { 
        if(d.part_level>0){return d.name;}
        else
            if(d.part_multi>1){return "Part " + d.name+ " ["+d.part_multi+"]";}
        else{return "Part " + d.name;}
    })
    .attr("title", 
          function(d){ 
              var node_type_desc;
              if(d.part_level!=0){node_type_desc = "Labour";}else{node_type_desc = "Component";}
              return ("Part Name: "+d.text+"<br/>Part type: "+d.part_type+"<br/>Cost so far: "+d3.round(d.part_cc, 2)+"&euro;<br/>"+"<br/>"+node_type_desc+" cost at this node: "+d3.round(d.part_cost, 2)+"&euro;<br/>"+"Total cost added by this node: "+d3.round(d.part_cost*d.part_multi, 2)+"&euro;<br/>"+"Node multiplicity: "+d.part_multi);
          })
    .style("fill-opacity", 1e-6);
    
    // Transition nodes to their new position.
    var nodeUpdate = node.transition()
    .duration(duration)
    .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });
    
    nodeUpdate.select("circle")
    .attr("r", function(d){ 
        return  Math.sqrt((d.part_cc_p*1))+4;
    })
    .attr("class", function(d) { return "level"+d.part_level; })
    .style("stroke", function(d){
        if(d._children){return "blue";}else{return null;}
    })
    ;
    
    nodeUpdate.select("text")
    .style("fill-opacity", 1);
    
    // Transition exiting nodes to the parent's new position.
    var nodeExit = node.exit().transition()
    .duration(duration)
    .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
    .remove();
    
    nodeExit.select("circle")
    .attr("r", function(d){ 
        return  Math.sqrt((d.part_cc_p*1))+4;
    });
    
    nodeExit.select("text")
    .style("fill-opacity", 1e-6);
    
    // Update the links…
    var link = vis.selectAll("path.link")
    .data(tree.links(nodes), function(d) { return d.target.id; });
    
    // Enter any new links at the parent's previous position.
    link.enter().insert("svg:path", "g")
    .attr("class", "link")
    .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
    })
    .transition()
    .duration(duration)
    .attr("d", diagonal);
    
    // Transition links to their new position.
    link.transition()
    .duration(duration)
    .attr("d", diagonal);
    
    // Transition exiting nodes to the parent's new position.
    link.exit().transition()
    .duration(duration)
    .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
    })
    .remove();
    
    // Stash the old positions for transition.
    nodes.forEach(function(d) {
        d.x0 = d.x;
        d.y0 = d.y;
    });
    
    d3.select("svg")
        .call(d3.behavior.zoom()
              .scaleExtent([15, 15])
              .on("zoom", zoom));
    
}

function zoom() {
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
        .attr("transform", "translate(" + translation + ")" +
              " scale(" + scale + ")");
}

