<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Table to CSV Generator</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th v-for="column in columns">
                                    <input type="text"
                                           class="form-control"
                                           :value="column.key"
                                           @input="updateColumnKey(column, $event)"
                                    />
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in data">
                                <td v-for="(dataColumn, columnName) in row">
                                    <input type="text" class="form-control" v-model="row[columnName]"/>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger" @click="removeRow(index)">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-secondary" @click="addColumn()">Add Column</button>
                        <button type="button" class="btn btn-secondary" @click="addRow()">Add Row</button>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="button" @click="submit()">Export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "CSVGenerator",

        data() {
            return {
                data: [
                    {
                        firstName: 'John',
                        lastName: 'Doe',
                        emailAddress: 'john.doe@example.com'
                    },
                ],
                columns: [
                    {key: 'firstName'},
                    {key: 'lastName'},
                    {key: 'emailAddress'},
                ],
                maxColumnsLength: 10,
            }
        },

        methods: {
            addRow() {
                let newRow = {};
                this.columns.forEach(column => newRow[column.key] = "");

                this.data.push(newRow);
            },

            removeRow(rowIndex) {
                if (this.data[rowIndex]) this.$delete(this.data, rowIndex);
            },

            addColumn() {
                let columnsLength = this.columns.length;

                if (columnsLength > this.maxColumnsLength) return;

                const newKey = "column" + ++columnsLength;

                this.columns.push({key: newKey});
                this.data.forEach(row => row[newKey] = "");
            },

            updateColumnKey(column, event) {
                let oldKey = column.key;

                let columnKeyExists = !!this.columns.find(column => column.key === event.target.value);

                column.key = event.target.value;

                if (columnKeyExists) {
                    column.key = event.target.value.substring(0, event.target.value.length - 1);
                    return;
                }

                this.data.forEach(
                    (row) => {
                        if (row[oldKey]) {
                            row[column.key] = row[oldKey];
                            delete row[oldKey];
                        }
                    }
                )
            },

            fileDownload(response) {
                console.log(response);
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');

                link.href = url;
                link.setAttribute('download', 'file.csv');
                document.body.appendChild(link);

                link.click();
            },

            submit() {
                axios.patch('/api/csv-export', this.data)
                    .then(response => this.fileDownload(response))
                    .catch(error => alert(error));
            }
        },
    }
</script>
