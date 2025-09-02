<style>
   @page {
    margin-top: 120px;   /* prostor za header */
    margin-bottom: 100px; /* prostor za footer */
    margin-left: 60px;
    margin-right: 60px;
    }

    header {
        position: fixed;
        top: -100px; /* isto kao margin-top */
        left: 0;
        right: 0;
        height: 100px;
        text-align: center;
    }

    footer {
        position: fixed; 
        bottom: -100px; 
        left: 0;
        right: 0;
        height: 100px;
        border-top: solid 1px black;
        padding-top: 10px;
        font-size: 8px; /* probaj 8px ako želiš baš sitno */
        line-height: 1.2;
        text-align: center;
        color: #000;
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
        /* margin-top: 2.25rem;  Unused */
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
        padding: 0.2rem;
        font-weight: bold;
    }
    table tr.items {
        background-color: rgb(241 245 249);
        vertical-align: top;
    }
    table tr.items td {
        padding: 0.1rem 0.5rem;
        border-bottom: solid 1px #a8a8a8;
    }
    table tr.total td {
        padding: 0.25rem 0.5rem;
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
    .relative-section {
        position: relative;
    }
    .mp-image {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%); /* Center within the cell */
    }
    .signature-image {
        position: absolute;
        top:-50px;
        left: 50%;
        transform: translateX(-50%); /* Center within the cell */
    }
</style>