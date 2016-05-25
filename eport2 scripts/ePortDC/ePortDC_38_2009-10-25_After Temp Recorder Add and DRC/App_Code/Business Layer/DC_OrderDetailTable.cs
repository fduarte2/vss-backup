// This is a "safe" class, meaning that it is created once 
// and never overwritten. Any custom code you add to this class 
// will be preserved when you regenerate your application.
//
// Typical customizations that may be done in this class include
//  - adding custom event handlers
//  - overriding base class methods

using System;
using System.Data.SqlTypes;
using BaseClasses;
using BaseClasses.Data;
using BaseClasses.Data.SqlProvider;

namespace ePortDC.Business
{

/// <summary>
/// Provides access to the schema information and record data of a database table (or view).
/// See <see cref="BaseDC_OrderDetailTable"></see> for additional information.
/// </summary>
/// <remarks>
/// See <see cref="BaseDC_OrderDetailTable"></see> for additional information.
/// <para>
/// This class is implemented using the Singleton design pattern.
/// </para>
/// </remarks>
/// <seealso cref="BaseDC_OrderDetailTable"></seealso>
/// <seealso cref="BaseDC_OrderDetailSqlTable"></seealso>
/// <seealso cref="DC_OrderDetailSqlTable"></seealso>
/// <seealso cref="DC_OrderDetailDefinition"></seealso>
/// <seealso cref="DC_OrderDetailRecord"></seealso>
/// <seealso cref="BaseDC_OrderDetailRecord"></seealso>
[SerializableAttribute()]
public class DC_OrderDetailTable : BaseDC_OrderDetailTable, System.Runtime.Serialization.ISerializable, ISingleton
{

#region "ISerializable Members"

    /// <summary>
    /// Overridden to use the <see cref="DC_OrderDetailTable_SerializationHelper"></see> class 
    /// for deserialization of <see cref="DC_OrderDetailTable"></see> data.
    /// </summary>
    /// <remarks>
    /// Since the <see cref="DC_OrderDetailTable"></see> class is implemented using the Singleton design pattern, 
    /// this method must be overridden to prevent additional instances from being created during deserialization.
    /// </remarks>
    void System.Runtime.Serialization.ISerializable.GetObjectData(
        System.Runtime.Serialization.SerializationInfo info, 
        System.Runtime.Serialization.StreamingContext context)
    {
        info.SetType(typeof(DC_OrderDetailTable_SerializationHelper)); //No other values need to be added
    }

#region "Class DC_OrderDetailTable_SerializationHelper"

    [SerializableAttribute()]
    private class DC_OrderDetailTable_SerializationHelper: System.Runtime.Serialization.IObjectReference
    {
        //Method called after this object is deserialized
        public virtual object GetRealObject(System.Runtime.Serialization.StreamingContext context)
        {
            return DC_OrderDetailTable.Instance;
        }
    }

#endregion

#endregion

    /// <summary>
    /// References the only instance of the <see cref="DC_OrderDetailTable"></see> class.
    /// </summary>
    /// <remarks>
    /// Since the <see cref="DC_OrderDetailTable"></see> class is implemented using the Singleton design pattern, 
    /// this field is the only way to access an instance of the class.
    /// </remarks>
    public readonly static DC_OrderDetailTable Instance = new DC_OrderDetailTable();

    private DC_OrderDetailTable()
    {
    }


} // End class DC_OrderDetailTable

}
