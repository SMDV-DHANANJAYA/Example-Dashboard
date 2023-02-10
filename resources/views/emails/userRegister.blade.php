<x-mail::message>
# User Registration<br>

Hi {{ $user['full_name'] }},<br>

Install the Odel FS User app and log in with these details.<br>
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
