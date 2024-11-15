<style>
    @page {
        margin: 100px 60px;
    }
    header {
        position: fixed;
        top: -60px;
        left: 0px;
        right: 0px;
        height: 50px;
        text-align: center;
    }
    footer {
        position: fixed; 
        bottom: -90px; 
        left: 0px; 
        right: 0px;
        height: 50px;
        border-top: solid 1px black;
        padding-top: 10px;
        color: black;
        text-align: center;            
        font-size: 0.5rem;
    }
    body {
        /*font-family: 'DejaVu Sans';*/
        /*font-family: 'Open Sans', sans-serif;*/
        font-family: 'DejaVu Sans', 'sans-serif';
    }
    h4 {
        margin: 0;
    }
    .center {
        text-align: center;
    }
    .right {
        text-align: right;
    }
    .info {
        font-size: 0.7rem;
    }
    .bottom {
        width: 100%;
        position: fixed;
        bottom: 0;
        padding-top: 1rem;
        padding-bottom: 1rem;
        background-color: rgb(241 245 249);
    }
    .w-full {
        width: 100%;
    }
    .w-half {
        width: 50%;
    }
    .w-tri {
        width: 32%;
        vertical-align: top;
    }
    .margin-top {
        margin-top: 2.25rem;
    }
    .margin-first {
        margin-top: 2.25rem;
    }
    .margin-signatures {
        margin-top: 6rem;
    }
    table {
        width: 100%;
        border-spacing: 0;
    }
    table.products {
        font-size: 0.7rem;
        text-align: left;
    }
    table.products th {
        background-color: gray;
        color: #ffffff;
        padding: 0.5rem;
        font-weight: bold;
    }
    table tr.items {
        background-color: rgb(241 245 249);
        vertical-align: top;
    }
    table tr.items td {
        padding: 0.5rem;
    }
    table tr.total td {
        padding: 0.5rem;
    }
    table tr.total td.totalAmount {
        background-color: #dce1e6;
    }
    table tr.total td.totalAmountText {
        text-align: right;
    }
    .notes {
        text-align: left;
        margin-top: 1rem;
        font-size: 0.7rem;
    }
    .gray-overlay {
        background-color: gray;
        color: white;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        border-radius: 0.2rem;
    }
    .signature-section {
        position: relative;
    }
    .mp-image {
        position: absolute;
        left: 50%;
        transform: translateX(-50%); /* Center within the cell */
    }
    .signature-image {
        position: absolute;
        left: 50%;
        transform: translateX(-50%); /* Center within the cell */
    }
</style>