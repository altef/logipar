var Table = function(declaration) {
	this.def = {
		data: [],
		numbers: [], // Which columns hold numbers
		headings: null, // Which headings to display, if you don't want all of them
		sortField: null,
		sortDirection: 1,
		container: null,
		defaultSortDirection: 1
	}
	
	for(var prop in declaration)
		if (this.def.hasOwnProperty(prop))
			this.def[prop] = declaration[prop];
		
	
	this.updateData = function(data) {
		this.def['data'] = data;
		this.sort();
	},
	
	
	
	this.sort = function() {
		if (this.def['sortField'] != null) {
			var field = this.def['sortField'];
			var order = this.def['sortDirection'];
			var numbers = this.def['numbers'];
			
			this.def['data'].sort(function(a,b) {
				var f = field;
				var one = 0;
				var two = 0;
				if (numbers.includes(f)) {
					one = parseInt(a[f] || 0);
					two = parseInt(b[f] || 0);
				} else {
					one = "" + a[f] || '';
					two = "" + b[f] || '';
				}
				
				if (typeof one === 'string' || one instanceof String) {		// The rest can just be strings
					return order * one.localeCompare(two);
				}
				
				return order * (one-two);
			});
		}
		this.display();
	}

	this.wrap = function(str) {
		return str;
	}


	this.toggleSort = function(field) {
		if (field == this.def['sortField']) {
			this.def['sortDirection'] *= -1;
		} else {
			this.def['sortField'] = field;
			this.def['sortDirection'] = this.def['defaultSortDirection'];
		}
		this.sort();
	}
	
	this.display = function() {
		console.log("displaying");
		// Create the HTML
		var data = this.def['data'];
		var out = "";
		if (data.length == 0) {
			out += "<p>No entries.</p>";
		} else {
			out += "<p>Total: "+data.length+"</p>\n";
			out += "<table>\n\t<thead>\n\t\t<tr>";
			
			// Headings
			var headings = this.def.headings;
			if (headings.length == 0)
				for(var prop in data[0]) 
					headings.push(prop);
				
			
			for(var i=0; i < headings.length; i++) {
				out += "\n\t\t\t<th class=\""+headings[i]+"\">" + headings[i] + "</th>";
			}
			out += "\n\t\t</tr>\n\t</thead>\n\t<tbody>";
			
			
			// Display data
			for(var t=0; t < data.length; t++) {
				out += "\n\t\t<tr>";
				for(var i=0; i < headings.length; i++) {
					value = data[t][headings[i]];
					out += "\n\t\t\t<td class=\""+headings[i]+"\">" + this.wrap(value) + "</td>";
				}
				out += "\n\t\t</tr>";
			}
			out += "\n\t</tbody>\n</table>\n";
		}
		$(this.def.container).html(out);
		var s = this;
		$(this.def.container).find('th').each(function(i) {
			$(this).on('click', s.toggleSort.bind(s, $(this).attr('class')));
		});
	}
}