<x-mail::message>
# Admin Registration<br>

Hi {{ $user['full_name'] }},<br>

Install the Odel FS Admin app, or you can access the Odel FS Admin panel using these details.<br>
<table>
    <tr>
        <td>Email</td>
        <td>:- {{ $user['email'] }}</td>
    </tr>
    <tr>
        <td>Password</td>
        <td>:- {{ $user['password'] }}</td>
    </tr>
</table>
<br>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
