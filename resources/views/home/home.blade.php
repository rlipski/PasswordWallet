@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
                <h3>Passwords</h3>
                <div>
                  <table class='table-passwords'>
                    <tr>
                      <th>Web address</th>
                      <th>Login</th>
                      <th>Description</th>
                      <th>Password</th>
                      <th>Action</th>
                    </tr>
                  @if ($passwords)
                  @foreach ($passwords as $password)
                    <tr>
                      <td>{{ $password->web_address }}</td>
                      <td>{{ $password->login }}</td>
                      <td>{{ $password->description }}</td>
                      <td>{{ substr($password->password, 0, 30) }}...</td>
                      <td>
                        <button class="btn btn-success btn-decryptPassword" id="btn-decryptPassword-{{ $password->id }}" data-password-id="{{ $password->id }}">Decrypt</button>
                        <button class="btn btn-success btn-decryptPassword" id="btn-decryptPassword-{{ $password->id }}" data-password-id="{{ $password->id }}">
                          <a href="/password/delete/{{ $password->id }}" >Delete</a>
                        </button>
                      </td>
                    </tr>
                  @endforeach
                  @endif
                  </table>
                  <a href='/password/create' >Create password</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- The Modal -->
    <div id="myModal" class="div-modal">
      <!-- Modal content -->
      <div class="div-modal-content">
        <span class="close">&times;</span>
        <p id="decryptedPassword"></p>
      </div>
    </div>
</div>
@endsection