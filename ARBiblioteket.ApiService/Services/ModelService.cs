public class ModelService : IModelService
{
    private readonly List<Model> _models = new();

    public async Task<Model?> GetModel(int id)
    {
        return await Task.FromResult(_models.FirstOrDefault(m => m.Id == id));
    }

    public async Task<List<Model>> GetAllModels()
    {
        return await Task.FromResult(_models);
    }

    public async Task<Model> CreateModel(ModelDto modelDto)
    {
        var model = new Model
        {
            Id = _models.Count + 1,
            Title = modelDto.Title,
            Description = modelDto.Description,
            ThreeDFile = modelDto.ThreeDFile,
            ImageFile = modelDto.ImageFile,
            UploaderId = modelDto.UploaderId,
            DateUploaded = DateTime.UtcNow,
            DateLastEdited = DateTime.UtcNow
        };

        _models.Add(model);
        return await Task.FromResult(model);
    }

    public async Task<bool> UpdateModel(int id, ModelDto modelDto)
    {
        var model = await GetModel(id);
        if (model == null) return false;

        model.Title = modelDto.Title;
        model.Description = modelDto.Description;
        model.ThreeDFile = modelDto.ThreeDFile;
        model.ImageFile = modelDto.ImageFile;
        model.DateLastEdited = DateTime.UtcNow;

        return true;
    }

    public async Task<bool> DeleteModel(int id)
    {
        var model = await GetModel(id);
        if (model == null) return false;

        _models.Remove(model);
        return true;
    }
} 