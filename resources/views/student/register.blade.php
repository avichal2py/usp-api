<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      padding: 40px;
    }
    form {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      max-width: 700px;
      margin: auto;
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    label {
      font-weight: bold;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 4px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .row {
      display: flex;
      gap: 20px;
    }
    .row > div {
      flex: 1;
    }
    button {
      padding: 10px;
      width: 100%;
      background-color: #29a3a3;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .error {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <form action="{{ route('student.register.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <h2>Student Registration</h2>

    @if ($errors->any())
      <div class="error">{{ $errors->first() }}</div>
    @endif

    <div class="row">
      <div>
        <label>First Name</label>
        <input type="text" name="first_name" required value="{{ old('first_name') }}">
      </div>
      <div>
        <label>Middle Name</label>
        <input type="text" name="middle_name" value="{{ old('middle_name') }}">
      </div>
    </div>

    <label>Last Name</label>
    <input type="text" name="last_name" required value="{{ old('last_name') }}">

    <label>Date of Birth</label>
    <input type="date" name="dob" required value="{{ old('dob') }}">

    <label>Gender</label>
    <select name="gender" required>
      <option value="">-- Select --</option>
      <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
      <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
      <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>

    <label>Email Address</label>
    <input type="email" name="email_address" required value="{{ old('email_address') }}">

    <label>Phone</label>
    <input type="text" name="phone" required value="{{ old('phone') }}">

    <label>Citizenship</label>
    <input type="text" name="citizenship" value="{{ old('citizenship') }}">

    <label>Program</label>
    <select name="program_id" required>
      <option value="">-- Select Program --</option>
      @foreach ($programs as $program)
        <option value="{{ $program->program_id }}" {{ old('program_id') == $program->program_id ? 'selected' : '' }}>
          {{ $program->program_name }}
        </option>
      @endforeach
    </select>

    <label>Residential Address</label>
    <input type="text" name="residential_address" value="{{ old('residential_address') }}">

    <label>Postal Address</label>
    <input type="text" name="postal_address" value="{{ old('postal_address') }}">

    <div class="row">
      <div>
        <label>City</label>
        <input type="text" name="city" value="{{ old('city') }}">
      </div>
      <div>
        <label>Nation</label>
        <input type="text" name="nation" value="{{ old('nation', 'Fiji') }}">
      </div>
    </div>

    <h3>Emergency Contact</h3>

    <label>EC First Name</label>
    <input type="text" name="ec_firstname" value="{{ old('ec_firstname') }}">

    <label>EC Last Name</label>
    <input type="text" name="ec_lastname" value="{{ old('ec_lastname') }}">

    <label>EC Other Name</label>
    <input type="text" name="ec_othername" value="{{ old('ec_othername') }}">

    <label>EC Relationship</label>
    <input type="text" name="ec_relationship" value="{{ old('ec_relationship') }}">

    <label>EC Residential Address</label>
    <input type="text" name="ec_residential_address" value="{{ old('ec_residential_address') }}">

    <div class="row">
      <div>
        <label>EC City</label>
        <input type="text" name="ec_city" value="{{ old('ec_city') }}">
      </div>
      <div>
        <label>EC Nation</label>
        <input type="text" name="ec_nation" value="{{ old('ec_nation', 'Fiji') }}">
      </div>
    </div>

    <label>EC Phone</label>
    <input type="text" name="ec_phone" value="{{ old('ec_phone') }}">

    <label>Upload Tertiary Qualification</label>
    <input type="file" name="tertiary_qualification" accept=".pdf,.jpg,.jpeg,.png,.docx">

    <button type="submit">Submit Application</button>
  </form>

</body>
</html>
