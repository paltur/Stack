@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="d-flex align-items-center">
                                <h2>{{$question->title}}</h2>
                                <div class="ml-auto">
                                    <a href="{{route('questions.index')}}" class="btn btn-outline-secondary">Back To All
                                        Question</a>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="media">
                            <div class="d-flex flex-column votes-control">
                                <a class="vote-up
                           {{Auth::guest() ? 'off' : ''}}"
                                   onclick="event.preventDefault(); document.getElementById('up-vote-question-{{$question->id}}').submit();"
                                >
                                    <i class="fas fa-caret-up fa-3x"></i>

                                </a>
                                <form action="/questions/{{$question->id}}/vote" style="display: none;" method="post"
                                      id="up-vote-question-{{$question->id}}">
                                    @csrf
                                    <input type="hidden" name="vote" value="1">
                                </form>
                                <span class="votes-count">{{$question->votes_count}}</span>
                                <a class="vote-down
                           {{Auth::guest() ? 'off' : ''}}"
                                   onclick="event.preventDefault(); document.getElementById('down-vote-question-{{$question->id}}').submit();"
                                >
                                    <i class="fas fa-caret-down fa-3x"></i>
                                </a>
                                <form action="/questions/{{$question->id}}/vote" style="display: none;" method="post"
                                      id="down-vote-question-{{$question->id}}">
                                    @csrf
                                    <input type="hidden" name="vote" value="-1">
                                </form>
                                <a class="favorite mt-3
                          {{Auth::guest() ? 'off':($question->isFavorited) ? 'favorited' : ''}} "
                                   onclick="event.preventDefault(); document.getElementById('favorie-question-{{$question->id}}').submit();">
                                    <i class="fas fa-star fa-2x"></i>

                                    <span class="favorites-count">{{$question->favorites_count}}</span>
                                </a>
                                <form action="/questions/{{$question->id}}/favorites" style="display: none;"
                                      method="post" id="favorie-question-{{$question->id}}">
                                    @csrf
                                    @if($question->is_favorited)
                                        @method('DELETE')
                                    @endif
                                </form>

                            </div>
                            <div class="media-body">
                                {!! $question->body_html !!}
                                <div class="float-right">
                                    <user-info :model="{{ $question }}" label="Asked"></user-info>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!------------------- Answer Section ------------------------------->
        <div class="row mt-5" v-cloak>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h2>Your Answer</h2>
                            @include('layouts._message')
                            <form action="{{route('questions.answers.store',$question->id)}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <textarea class="form-control {{$errors->has('body') ? 'is-invalid': ''}}" rows="7"
                                              name="body"></textarea>
                                    @if($errors->has('body'))
                                        <div class="invalid-feedback">
                                            <strong>{{$errors->first('body')}}</strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-secondary">Submit</button>
                                </div>
                            </form>
                            <hr>
                            <h3>{{$question->answers_count}}
                                {{str_plural('Answer',$question->answers_count)}}
                            </h3>
                        </div>
                        <hr>
                        @foreach($question->answers as $answer)
                            <div class="media">
                                <div class="d-flex flex-column votes-control">
                                    <a class="vote-up
                           {{Auth::guest() ? 'off' : ''}}"
                                       onclick="event.preventDefault(); document.getElementById('up-vote-answer-{{$answer->id}}').submit();"
                                    >
                                        <i class="fas fa-caret-up fa-3x"></i>

                                    </a>
                                    <form action="/answers/{{$answer->id}}/vote" style="display: none;" method="post"
                                          id="up-vote-answer-{{$answer->id}}">
                                        @csrf
                                        <input type="hidden" name="vote" value="1">
                                    </form>
                                    <span class="votes-count">{{$answer->votes_count}}</span>
                                    <a class="vote-down
                           {{Auth::guest() ? 'off' : ''}}"
                                       onclick="event.preventDefault(); document.getElementById('down-vote-answer-{{$answer->id}}').submit();"
                                    >
                                        <i class="fas fa-caret-down fa-3x"></i>
                                    </a>
                                    <form action="/answers/{{$answer->id}}/vote" style="display: none;" method="post"
                                          id="down-vote-answer-{{$answer->id}}">
                                        @csrf
                                        <input type="hidden" name="vote" value="-1">
                                    </form>

                                    @can('accept', $answer)
                                        <a class="{{$answer->status}} mt-3"
                                           onclick="event.preventDefault(); document.getElementById('accept-answer-{{$answer->id}}').submit();">
                                            <i class="fas fa-check fa-2x"></i>
                                        </a>
                                        <form action="{{route('answers.accept',$answer->id)}}" style="display: none;"
                                              method="post" id="accept-answer-{{$answer->id}}">
                                            @csrf
                                        </form>
                                    @else
                                        @if($answer->is_best)
                                            <a class="{{$answer->status}} mt-3">
                                                <i class="fas fa-check fa-2x"></i>
                                            </a>
                                        @endif
                                    @endcan
                                </div>
                                <answer :answer="{{$answer}}" inline-template>
                                    <div class="media-body">
                                        <form v-if="editing" @submit.prevent="update">
                                            <div class="form-group">
                                                <textarea required rows="10" v-model="body" class="form-control"></textarea>
                                            </div>
                                            <button class="btn btn-outline-primary"
                                                    :disabled="isInvalid">Update</button>
                                            <button type="button" class="btn btn-outline-secondary"
                                                    @click="cancel">Cancel</button>
                                        </form>
                                        <div v-else>
                                            <div v-html="bodyHtml"></div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="ml-auto">
                                                        @can('update',$answer)
                                                            <a @click.prevent="edit" class="btn btn-outline-info
                                                            btn-sm">Edit</a>
                                                        @endcan
                                                        @can('delete',$answer)
                                                            <button @click="destroy" class=" btn btn-sm
                                                            btn-outline-danger">Delete</button>
                                                        @endcan
                                                    </div>
                                                </div>
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4">
                                                    <div class="float-right">
                                                        <user-info :model="{{ $answer }}" label="Asked"></user-info>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </answer>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
