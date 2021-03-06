"""
Automatically Generated by Emb3rs Platform
generated at : {{ $now }}
"""

import numpy as np
import pandas as pd
import glob
import json

"""
VARIABLES FROM SOURCE:
"""
@foreach ($model->template->templateProperties as $tprop)
@php
    $prop = $tprop->property
@endphp
{{--



 --}}
@if ($prop->inputType === "text")
@if ($prop->dataType === "number")
{{ $prop->symbolic_name }} = {{ $model->values["info"][$prop->symbolic_name] }}
@else
{{ $prop->symbolic_name }} = "{{ $model->values["info"][$prop->symbolic_name] }}"
@endif
@elseif($prop->inputType === "select")
{{ $prop->symbolic_name }} = {{ $model->values["info"][$prop->symbolic_name]["key"] }}
@endif
@endforeach

"""
Source Script
"""

@foreach ($sourceScripts as $sourceScript)
{!! $sourceScript->code !!}
@endforeach


