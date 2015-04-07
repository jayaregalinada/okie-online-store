@extends('app')

@section('content')

<div class="container-fluid msg-container content-container">
    <div class="msg-nav col-md-3">
        <button class="btn btn-block btn-primary"><i class="fa fa-pencil"></i> NEW MESSAGE</button>
        <br />
        <div class="navigation">
            <div class="list-group">
                <a href="#inbox" class="list-group-item">
                    <span class="badge">20</span>
                    <i class="msg-nav-icon fa fa-inbox"></i> Inbox
                </a>
                <a href="#inquiries" class="list-group-item">
                    <span class="badge"></span>
                    <i class="msg-nav-icon fa fa-star"></i> Inquiries
                </a>
                <a href="#delivered" class="list-group-item">
                    <span class="badge">3</span>
                    <i class="msg-nav-icon fa fa-thumbs-up"></i> Delivered
                </a>
            </div>
        </div>
    </div>
    <div class="messages-container col-md-9">
        <header class="messages-header">Inbox</header>
        <div class="messages-controls clearfix">
            <div class="btn-group" role="controls">
                <button type="button" class="control btn-sm btn btn-primary"><i class="fa fa-angle-left"></i></button>
            </div>
        </div>
        <div class="messages-list clearfix">
            <ul class="list-group">
                
            </ul>
        </div>
    </div>
</div>

@endsection
