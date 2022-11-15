<div>

    @if(isset($importFeed['mapped_content']))


        <div class="mb-2 text-center">
            <b>{{count($importFeed['mapped_content'])}} products are imported</b>
        </div>

        <table class="table">
            <thead>
            <tr>
                @foreach($importFeed['mapped_content'][0] as $field=>$fieldValue)

                    @if (is_string($field) && is_string($fieldValue))
                        <td>{{$field}} </td>
                    @endif

                    @if (is_array($fieldValue))
                        @foreach($fieldValue as $mlField=>$mlFieldValue)
                          <td>{{$mlField}}</td>
                        @endforeach
                    @endif

                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($importFeed['mapped_content'] as $content)
                <tr>
                    @if(isset($content['pictures']))
                        <td>
                            @if(is_array($content['pictures']))
                                @foreach($content['pictures'] as $picture) <img src="{{$picture}}" style="width:120px;" /> @endforeach
                            @endif
                        </td>
                    @endif

                    @if(isset($content['title']))
                    <td>  {{$content['title']}}   </td>
                    @endif

                    @if(isset($content['multilanguage']['title']))
                            <td>
                        @foreach($content['multilanguage']['title'] as $locale=>$value)

                        @if(!empty($value))
                         {{$value}}
                        @else
                            <span class="text-muted"> No title in feed</span>
                        @endif
                                [{{$locale}}]   <br />
                        @endforeach
                        </td>
                    @endif

                    @if(isset($content['multilanguage']['description']))
                        <td>
                            @foreach($content['multilanguage']['description'] as $locale=>$value)

                                @if(!empty($value))
                                    {{$value}}
                                @else
                                    <span class="text-muted"> No description in feed</span>
                                @endif
                                [{{$locale}}]   <br />
                            @endforeach
                        </td>
                    @endif

                    @if(isset($content['content_body']))
                    <td> {{$content['content_body']}}  </td>
                    @endif

                    @if(isset($content['price']))
                    <td>{{$content['price']}}   </td>
                    @endif
{{--
                    @if(isset($content['created_at']))
                    <td> {{$content['created_at']}} </td>
                    @endif

                    @if(isset($content['updated_at']))
                    <td> {{$content['updated_at']}} </td>
                    @endif--}}

                </tr>
            @endforeach
            </tbody>
        </table>

    @endif
</div>
