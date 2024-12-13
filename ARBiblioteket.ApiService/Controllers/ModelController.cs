using ARBiblioteket.ApiService.Services;
using ARBiblioteket.ApiService.Models;
using ARBiblioteket.ApiService.Mapping;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;

[ApiController]
[Route("api/[controller]")]
public class ModelController : ControllerBase
{
    private readonly ApplicationDbContext _DbContext;
    private readonly ModelMapping _modelMapping;

    public ModelController(ApplicationDbContext DbContext, ModelMapping modelMapping)
    {
        _DbContext = DbContext;
        _modelMapping = modelMapping;
    }
    //Get model by ID
    [HttpGet("{id}")]
    public async Task<ActionResult<Model>> GetModel(int id)
    {
        var model = await _DbContext.Models.FindAsync(id);
        if (model == null) return NotFound();
        return Ok(model);
    }
    //Get all models
    [HttpGet]
    public async Task<ActionResult<List<Model>>> GetAllModels()
    {
        return Ok(await _DbContext.Models.ToListAsync());
    }
    //Create model
    [HttpPost]
    public async Task<IActionResult> Post([FromBody] Model model)
    {
        _DbContext.Models.Add(model);
        _DbContext.SaveChanges();
        return Ok(model);
    }
    //Edit model by ID
    [HttpPut("{id}")]
    public async Task<IActionResult> UpdateModel([FromRoute] int id, [FromBody] Model model)
    {
        var result = await _DbContext.Models.FindAsync(id);
        if (result == null)
        {
            return NotFound();
        }

        // Update fields manually
        result.Title = model.Title;
        result.Description = model.Description;
        result.Education = model.Education;
        result.ThreeDFile = model.ThreeDFile;
        result.ImageFile = model.ImageFile;
        result.UploaderId = model.UploaderId;
        result.Uploaded = model.Uploaded;
        result.LastEdited = model.LastEdited;

        _DbContext.Models.Update(result);
        await _DbContext.SaveChangesAsync();

        return Ok(result);
    }
    //Delete user by ID
    [HttpDelete("{id}")]
    public async Task<ActionResult> DeleteModel(int id)
    {
        var model = await _DbContext.Models.FindAsync(id);
        if (model == null)
        {
            return NotFound();
        }

        _DbContext.Models.Remove(model);
        await _DbContext.SaveChangesAsync();
        return NoContent();
    }
} 