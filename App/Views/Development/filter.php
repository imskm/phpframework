<?php $this->use('templates/base.php', ['title' => 'Filter']) ?>

<div id="app" class="filter">
	<button class="filter-button" @click="showFilterDD = !showFilterDD" style="float: right">
		<div class="filter-icon"><i class="fa fa-filter"></i></div>
		<div class="filter-icon-angle"><i class="fa fa-angle-down"></i></div>
	</button>
	<div class="filter-dd" v-if="showFilterDD" style="float: right">
		<div style="width: 300px">
			<div class="card">
				<div class="card-body">
					<div class="form-control">
						<select v-model="field" name="field" id="" style="width: 100%">
							<option value="" selected="selected">---</option>
							<option v-for="(field, key) in fields" :value="key">{{ field.name }}</option>
						</select>
					</div>

					<div class="form-control">
						<select v-model="operator" name="operator" style="width: 100%">
							<option value="" selected="selected">---</option>
							<option v-for="op in active_operators" :value="op.key">{{ op.name }}</option>
						</select>
					</div>

					<div class="form-control" v-for="active_field in active_fields">
						<input v-model="criteria" :type="active_field.type" name="criteria" placeholder="Search" style="width: 100%">
					</div>

					<div class="form-control">
						<button @click="addFilter" type="button" class="btn btn-success">Add</button>
						<button @click="applyFilter" type="button" class="btn btn-success">Apply</button>
						<button @click="showFilterDD = !showFilterDD" type="button" class="btn btn-danger">Cancel</button>
					</div>
				</div>

			</div>
		</div>
	</div>
	<table class="table" width="100%">
		<thead>
			<tr>
				<th></th>
				<th></th>
				<th>Full name</th>
				<th>Created at</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>PHOTO</td>
				<td>Shek Muktar</td>
				<td>10/01/2019</td>
			</tr>

		</tbody>
		<tfoot>
			<tr>
				<th>TOTAL</th>
			</tr>
		</tfoot>
	</table>
	pagination
</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
var app = new Vue({
	el: '#app',
	data: {
		showFilterDD: false,
		field: "",
		criteria: "",
		operator: "",
		active_fields: [],
		filter_fields: [],
		active_operators: [],
		base_url: "/development/ajax-apply-filter",
		fields: {
			full_name: 		{name: "Full name", type: "text"},
			amount: 		{name: "Amount", type: "number"},
			created_at: 	{name: "Created at", type: "date"},
		},
		operators: {
			number: [
				{key: "gt", name: "Greater than"},
				{key: "lt", name: "Less than"},
				{key: "gte", name: "Greater than equal"},
				{key: "lte", name: "Less than equal"},
				{key: "eq", name: "Equal"},
				{key: "neq", name: "Not equal"},
			],
			date: [
				{key: "before", name: "Before"},
				{key: "after", name: "After"},
				// TODO will implement it later
				// {key: "between", name: "Between"},
				{key: "on", name: "On"},
			],
		}
	},
	methods: {
		addFilter: function() {
			console.log(this.field);
			console.log(this.operator);
			console.log(this.criteria);
			var url = this.base_url;

			// $_GET param in the server side
			// url => filters[list_of_fields_to_be_filtered_and_included_in_get_param]
			//     => field[operator, value_1, value_2,...]

			url += "?" + this.field + "[]=" + 
						(this.operator ? this.operator : "search");

			url += "&" + this.field + "[]=" + this.criteria;
			url += "&";

			for (var i = 0; i < this.filter_fields.length; i++) {
				url += "filters[]=" + this.filter_fields[i];
				if (this.filter_fields.length - 1 != i) {
					url += "&";
				}
			}

			console.log(url);
			this.applyFilter(url);
		},

		applyFilter: function(url) {

			axios.get(url)
			.then(function(response) {
				console.log(response);
			}).catch(function(error) {
				console.log(error);
			}).finally(function() {
				// 
			});

		},
	},

	watch: {
		field: function(key) {
			this.active_fields = [];
			this.active_operators = [];
			this.filter_fields = [];
			this.operator = "";
			if (this.fields[key] === undefined) {
				// TODO
				// clear the filter and fetch dataset for base url
				return;
			}
			var field  = this.fields[key];
			this.active_fields.push(this.fields[key]);
			this.active_operators = this.operators[this.fields[key].type];

			console.log(this.active_operators);
			this.filter_fields.push(key);

		}
	},

	mounted: function() {
	},

})
</script>