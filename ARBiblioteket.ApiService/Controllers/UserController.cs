using Microsoft.AspNetCore.Mvc;

[ApiController]
[Route("api/[controller]")]
public class UserController : ControllerBase
{
    private readonly IUserService _userService;

    public UserController(IUserService userService)
    {
        _userService = userService;
    }

    [HttpGet("{id}")]
    public async Task<ActionResult<User>> GetUser(int id)
    {
        var user = await _userService.GetUser(id);
        if (user == null) return NotFound();
        return Ok(user);
    }

    [HttpGet]
    public async Task<ActionResult<List<User>>> GetAllUsers()
    {
        return Ok(await _userService.GetAllUsers());
    }

    [HttpPost("register")]
    public async Task<ActionResult<User>> Register(UserDto userDto)
    {
        var user = await _userService.CreateUser(userDto);
        return CreatedAtAction(nameof(GetUser), new { id = user.Id }, user);
    }

    [HttpPost("login")]
    public async Task<ActionResult<bool>> Login(UserDto userDto)
    {
        var isValid = await _userService.ValidateUser(userDto.Username, userDto.Password);
        if (!isValid) return Unauthorized();
        return Ok(true);
    }

    [HttpPut("{id}")]
    public async Task<ActionResult> UpdateUser(int id, UserDto userDto)
    {
        var result = await _userService.UpdateUser(id, userDto);
        if (!result) return NotFound();
        return NoContent();
    }

    [HttpDelete("{id}")]
    public async Task<ActionResult> DeleteUser(int id)
    {
        var result = await _userService.DeleteUser(id);
        if (!result) return NotFound();
        return NoContent();
    }
} 