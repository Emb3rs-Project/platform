<head>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="d3" src="https://d3js.org/d3.v5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js" integrity="sha512-c3Nl8+7g4LMSTdrm621y7kf9v3SDPnhxLNhcjFJbKECVnmZHTdo+IRO05sNLTH/D3vA6u1X32ehoLC7WFVdheg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://mpld3.github.io/js/mpld3.v0.5.7.js" async=""></script>

</head>
<style id="plotly.js-style-global"></style>


    <div style="width:100%;display: flex; align-items: center; justify-items: center" class="full_page">
    <div>
        <img src="https://www.emb3rs.eu/wp-content/uploads/2019/12/EMB3Rs_horizontal-Logo.png" class="img">
        <h1 style="padding-left: 5px">Final Report</h1>
        <div style="padding-left: 5px">
            <span><strong>Project :</strong> {{$metadata['project']}}</span> <br>
            <span><strong>Simulation :</strong> {{$metadata['simulation']}}</span>
        </div>
    </div>
</div>
<div class="full_page">

    <div id="toc_container">
        <p class="toc_title">Summary</p>
        <ul class="toc_list">
            @foreach($reports as $report)
                <li style="text-align: left">
                    <a href="#{{\Illuminate\Support\Str::slug($report['module'])}}">{{$report['module']}} Report</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<style>
    .full_page {
        height: 1500px;
    }

    #toc_container {
        background: #f9f9f9 none repeat scroll 0 0;
        border: 1px solid #aaa;
        display: table;
        font-size: 95%;
        margin-bottom: 1em;
        padding: 20px;
        width: 100%;
    }

    .toc_title {
        font-weight: 700;
        text-align: center;
    }

    #toc_container li, #toc_container ul, #toc_container ul li {
        list-style: outside none none !important;
    }
</style>

@foreach($reports as $report)
    @if(\Illuminate\Support\Str::slug($report['module']) == 'teo-module')
    <div style="height: 50%">
    @else
    <div >
    @endif
        <div id="{{\Illuminate\Support\Str::slug($report['module'])}}">&nbsp</div>
        {!!$report['report'] !!}
        <div style="margin-top: 15%">&nbsp;</div>
    </div>
@endforeach


