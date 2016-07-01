@extends('layouts.app')
@section('title')
    Inbox
({{ Auth::user()->email }})
@endsection
@section('js')
    "{{asset('js/inbox.js')}}"
@endsection
@section('content')
    <div class="container">
    <div class="page-header">
        <h1>TMail <small>Simple way to destroy your mails :D</small></h1>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    Mail <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Mail</a></li>
                    <li><a href="#">Contacts</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-10">
            <!-- Split button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">All</a></li>
                    <li><a href="#">None</a></li>
                    <li><a href="#">Read</a></li>
                    <li><a href="#">Unread</a></li>
                    <li><a href="#">Starred</a></li>
                    <li><a href="#">Unstarred</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Refresh">
                <span class="glyphicon glyphicon-refresh"></span>
            </button>
            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    More <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Mark all as read</a></li>
                    <li class="divider"></li>
                    <li class="text-center"><small class="text-muted">Select messages to see more actions</small></li>
                </ul>
            </div>
            <div class="pull-right" id="pagination">
                <span class="text-muted"><b>@{{from}}</b>–<b>@{{to}}</b> of <b>@{{total}}</b></span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-default" v-on:click="back">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </button>
                    <button type="button" class="btn btn-default" v-on:click="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-2">
            <button type="button" class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#compose">
                <i class="glyphicon glyphicon-edit"></i> Compose
            </button>
            <hr>
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><span class="badge pull-right">32</span> Inbox </a>
                </li>
                <li><a href="#">Sent Mail</a></li>
            </ul>
        </div>
        <div class="col-md-10">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="glyphicon glyphicon-inbox"></span>
                    &nbspPrimary
                </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <!-- Inbox pane -->
                    <div class="list-group">
                        <template v-for="mail in mails">
                        <a href="#" data-toggle="modal" data-target="#mail-@{{mail._id}}" class="list-group-item">
                            <label>
                                <input type="checkbox">
                            </label>
                            <span class="name" style="min-width: 120px;display: inline-block;">@{{mail.author}}</span>
                            <span>@{{mail.title}}</span>
                            <span class="text-muted" style="font-size: 11px;">- @{{mail.head}}</span>
                            <span class="badge">@{{mail.created_at}}</span>
                            <span class="pull-right"><span v-show="mail.hasAttachment" class="glyphicon glyphicon-paperclip"></span></span>
                        </a>
                        <div class="modal fade" id="mail-@{{mail._id}}" tabindex="-1" role="dialog" aria-labelledby="compose-area">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="compose-area">@{{mail.title}}</h4><small>@{{mail.author}}</small>
                                    </div>
                                    <div class="modal-body">
                                        <div>@{{{mail.content}}}</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="compose" tabindex="-1" role="dialog" aria-labelledby="compose-area">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="compose-area">TMail at your service</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert" v-if="hasError">
                        <ul>
                            <li>Recipient: @{{errors.recipient}}</li>
                            <li>Title: @{{errors.title}}</li>
                            <li>Content: @{{errors.content}}</li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Recipient" id="compose-recipient">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Title" id="compose-title">
                    </div>
                    <div class="form-group">
                        <div class="form-control" id="compose-content"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="compose-send">Send</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
